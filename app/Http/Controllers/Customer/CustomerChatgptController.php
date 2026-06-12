<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Tour;
use App\Models\Promotion;
use App\Models\Setting;
use App\Models\ChatHistory;

class CustomerChatgptController extends Controller
{
    public function ask(Request $request)
    {
        $question = $request->input('question');

        if (!$question) {
            return response()->json(['error' => 'Vui lòng nhập câu hỏi.'], 400);
        }

        try {
            // 1. Lấy cấu hình hệ thống
            $settings = Setting::pluck('value', 'key')->all();
            $siteName = $settings['site_name'] ?? 'Travel';
            $hotline = $settings['contact_phone'] ?? 'Chưa cấu hình';
            $email = $settings['contact_email'] ?? 'Chưa cấu hình';
            $address = $settings['address'] ?? 'Chưa cấu hình';
            $bankInfo = $settings['bank_info'] ?? ($settings['bank_account'] ?? 'Chưa cấu hình');

            // 2. Lấy danh sách Tour đang hoạt động và lịch khởi hành sắp tới
            $tours = Tour::active()->with([
                'destinations',
                'departures' => function ($q) {
                    $q->where('start_date', '>=', now())->where('available_seats', '>', 0);
                }
            ])->get();

            $toursData = [];
            foreach ($tours as $tour) {
                $departuresStr = [];
                foreach ($tour->departures as $dep) {
                    $price = $dep->price_override ?? $tour->base_price;
                    $departuresStr[] = "Ngày: " . $dep->start_date->format('d/m/Y') . " (Giá: " . number_format($price) . "đ, Còn trống: " . $dep->available_seats . " chỗ)";
                }

                $destinations = $tour->destinations->pluck('name')->implode(', ');

                $toursData[] = "- Tên Tour: {$tour->title} (#T" . str_pad($tour->id, 4, '0', STR_PAD_LEFT) . ")
  + Thời gian: {$tour->duration} (Phương tiện: {$tour->transportation})
  + Điểm đến: {$destinations}
  + Giá gốc: " . number_format($tour->base_price) . "đ
  + Tóm tắt: {$tour->summary}
  + Dịch vụ bao gồm: " . strip_tags($tour->service_includes) . "
  + Dịch vụ không bao gồm: " . strip_tags($tour->service_excludes) . "
  + Lịch khởi hành sắp tới: " . (empty($departuresStr) ? "Liên hệ hotline để cập nhật lịch mới nhất" : implode('; ', $departuresStr));
            }
            $toursContext = implode("\n", $toursData);

            // 3. Lấy danh sách Khuyến mãi đang hoạt động
            $promotions = Promotion::where('expiry_date', '>=', now())->get();
            $promoData = [];
            foreach ($promotions as $promo) {
                $type = $promo->discount_type === 'Percentage' ? "%" : "đ";
                $promoData[] = "- Mã: {$promo->code} (Giảm {$promo->discount_value}{$type} - Hạn dùng: " . $promo->expiry_date->format('d/m/Y') . " - {$promo->description})";
            }
            $promoContext = implode("\n", $promoData);

            // 4. Xây dựng Prompt
            $systemPrompt = "Bạn là trợ lý ảo AI chăm sóc khách hàng của công ty du lịch {$siteName}.
Thông tin liên hệ của công ty:
- Hotline: {$hotline}
- Email: {$email}
- Địa chỉ: {$address}
- Tài khoản thanh toán/ngân hàng: {$bankInfo}

Hãy sử dụng thông tin danh sách các tour du lịch và chương trình khuyến mãi dưới đây để trả lời câu hỏi của khách hàng một cách thân thiện, nhiệt tình, chuyên nghiệp, ngắn gọn (sử dụng ngôn ngữ tự nhiên, tiếng Việt).
Lưu ý quan trọng:
1. Chỉ trả lời dựa vào các thông tin có trong danh sách. Nếu khách hỏi thông tin/tour không có, hãy lịch sự phản hồi là không có thông tin hoặc gợi ý họ gọi hotline {$hotline} để được tư vấn thêm.
2. Trả lời ngắn gọn, trực diện, không dài dòng lê thê. Không sử dụng định dạng Markdown quá phức tạp, giữ câu trả lời sạch sẽ.

DANH SÁCH TOUR DU LỊCH ĐANG HOẠT ĐỘNG:
{$toursContext}

DANH SÁCH MÃ GIẢM GIÁ:
{$promoContext}";

            // 5. Lấy API key theo thứ tự ưu tiên: DB settings > config (không dùng env() trực tiếp)
            $apiKey = trim((string) ($settings['openrouter_api_key'] ?? ''));
            if ($apiKey === '') {
                $apiKey = config('services.openrouter.key');
            }

            $model = trim((string) ($settings['openrouter_model'] ?? ''));
            if ($model === '') {
                $model = config('services.openrouter.model');
            }

            if (!$apiKey) {
                Log::warning('Chatbot: OpenRouter API key missing - not configured in DB settings or config/services.php');
                return response()->json([
                    'error' => 'Hệ thống AI chưa được cấu hình. Vui lòng liên hệ quản trị viên.'
                ], 500);
            }

            $endpoint = config('services.openrouter.base_url') . '/chat/completions';

            // 6. Gọi OpenRouter API với timeout
            $response = Http::connectTimeout(10)->timeout(30)->withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name', 'TourTravel'),
            ])->post($endpoint, [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $question],
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            if ($response->failed()) {
                $status = $response->status();
                Log::error('Chatbot: OpenRouter API failed', [
                    'status' => $status,
                    'body' => $response->body(),
                    'model' => $model,
                    'endpoint' => $endpoint,
                ]);

                // Phân biệt lỗi cho user theo status code
                $userMessage = match (true) {
                    in_array($status, [401, 403]) => 'Hệ thống AI chưa được cấu hình đúng. Vui lòng liên hệ quản trị viên.',
                    $status === 429 => 'Hệ thống AI đang bận hoặc vượt giới hạn, vui lòng thử lại sau.',
                    default => 'Máy chủ AI tạm thời không khả dụng, vui lòng thử lại sau.',
                };

                return response()->json(['error' => $userMessage], 500);
            }

            $data = $response->json();

            // Kiểm tra cấu trúc response hợp lệ
            if (!isset($data['choices'][0]['message']['content'])) {
                Log::error('Chatbot: OpenRouter invalid response structure', [
                    'body' => $response->body(),
                    'model' => $model,
                ]);
                return response()->json([
                    'error' => 'Máy chủ AI trả về dữ liệu không hợp lệ, vui lòng thử lại sau.'
                ], 500);
            }

            $answer = $data['choices'][0]['message']['content'];

            // Xóa các block tag suy nghĩ của DeepSeek r1 nếu có (ví dụ <think>...)
            $answer = preg_replace('/<think>.*?<\/think>/is', '', $answer);
            $answer = trim($answer);

            // 7. Lưu vào lịch sử Chat (chỉ lưu nếu đã đăng nhập)
            if (auth()->check()) {
                $chat = new ChatHistory();
                $chat->user_id = auth()->id();
                $chat->session_id = session()->getId();
                $chat->message = $question;
                $chat->reply = $answer;
                $chat->save();
            }

            return response()->json(['answer' => $answer]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Chatbot: Connection timeout to OpenRouter', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'error' => 'Máy chủ AI không phản hồi, vui lòng thử lại sau.'
            ], 500);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Chatbot: Database error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'error' => 'Hệ thống đang gặp sự cố, vui lòng thử lại sau.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Chatbot: Unexpected exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'error' => 'Không thể kết nối tới máy chủ AI. Vui lòng thử lại sau.'
            ], 500);
        }
    }

    public function history()
    {
        if (!auth()->check()) {
            return response()->json([]);
        }

        // Lấy tối đa 50 cuộc hội thoại gần nhất của user và sắp xếp theo thứ tự thời gian tăng dần
        $chats = ChatHistory::where('user_id', auth()->id())
            ->latest()
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($chats);
    }
}
