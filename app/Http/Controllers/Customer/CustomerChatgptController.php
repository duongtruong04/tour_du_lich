<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
                $tourUrl = route('public.tours.show', $tour->slug);

                $toursData[] = "- Tên Tour: {$tour->title} (#T" . str_pad($tour->id, 4, '0', STR_PAD_LEFT) . ")
  + URL chi tiết: {$tourUrl}
  + Thời gian: {$tour->duration} (Phương tiện: {$tour->transportation})
  + Điểm đến: {$destinations}
  + Giá gốc: " . number_format($tour->base_price) . "đ
  + Tóm tắt: {$tour->summary}
  + Lịch trình chi tiết: " . strip_tags($tour->itinerary ?? '') . "
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

            // 4. Phát hiện tour cụ thể từ câu hỏi và cập nhật session
            $this->detectAndSaveCurrentTour($question, $tours);

            // 5. Xây dựng phần TOUR ĐANG ĐƯỢC KHÁCH QUAN TÂM
            $currentTourContext = '';
            $currentTourId = session('chat_current_tour_id');
            if ($currentTourId) {
                $currentTour = $tours->firstWhere('id', $currentTourId);
                if ($currentTour) {
                    $ctDepartures = [];
                    foreach ($currentTour->departures as $dep) {
                        $price = $dep->price_override ?? $currentTour->base_price;
                        $ctDepartures[] = "Ngày: " . $dep->start_date->format('d/m/Y') . " (Giá: " . number_format($price) . "đ, Còn trống: " . $dep->available_seats . " chỗ)";
                    }
                    $ctDestinations = $currentTour->destinations->pluck('name')->implode(', ');
                    $ctUrl = route('public.tours.show', $currentTour->slug);

                    $currentTourContext = "\n\nTOUR ĐANG ĐƯỢC KHÁCH QUAN TÂM:
- Tên tour: {$currentTour->title} (#T" . str_pad($currentTour->id, 4, '0', STR_PAD_LEFT) . ")
- URL chi tiết: {$ctUrl}
- Thời gian: {$currentTour->duration}
- Phương tiện: {$currentTour->transportation}
- Giá gốc: " . number_format($currentTour->base_price) . "đ
- Điểm đến: {$ctDestinations}
- Tóm tắt: {$currentTour->summary}
- Lịch trình chi tiết: " . strip_tags($currentTour->itinerary ?? '') . "
- Dịch vụ bao gồm: " . strip_tags($currentTour->service_includes) . "
- Dịch vụ không bao gồm: " . strip_tags($currentTour->service_excludes) . "
- Lịch khởi hành sắp tới: " . (empty($ctDepartures) ? "Liên hệ hotline để cập nhật lịch mới nhất" : implode('; ', $ctDepartures));
                }
            }

            // 6. Xây dựng System Prompt
            $systemPrompt = "Bạn là chuyên viên tư vấn tour du lịch chuyên nghiệp của công ty {$siteName}. Nhiệm vụ của bạn là tư vấn tour dựa trên dữ liệu tour thật được hệ thống cung cấp.

Thông tin liên hệ của công ty:
- Hotline: {$hotline}
- Email: {$email}
- Địa chỉ: {$address}
- Tài khoản thanh toán/ngân hàng: {$bankInfo}

Quy tắc bắt buộc:
- Luôn trả lời bằng tiếng Việt, giọng thân thiện, rõ ràng.
- Chỉ sử dụng dữ liệu tour có trong phần 'Dữ liệu tour hiện có'.
- Không tự bịa tên tour, URL, giá, lịch trình, khuyến mãi hoặc dịch vụ.
- Nếu dữ liệu thiếu, hãy nói rõ hệ thống chưa có thông tin đó.
- Khi nhắc đến bất kỳ tour nào, bắt buộc chèn link Markdown dạng [Tên tour](URL chi tiết) bằng đúng URL được cung cấp trong trường 'URL chi tiết' của tour đó.
- Nếu khách hàng hỏi một tour cụ thể và trong dữ liệu có tour phù hợp rõ ràng, hãy ưu tiên trả lời tập trung vào tour đó và đưa đúng 1 link chi tiết tour.
- Nếu khách hàng hỏi chung chung, có thể đề xuất tối đa 3 tour phù hợp nhất, mỗi tour có link chi tiết nếu có.
- Nếu không tìm thấy tour phù hợp, không được bịa tour. Hãy nói chưa tìm thấy tour phù hợp và hỏi thêm nhu cầu hoặc gợi ý gọi hotline {$hotline}.
- Chỉ dùng Markdown đơn giản: **in đậm**, xuống dòng, và link Markdown. Không dùng HTML.

Quy tắc ngữ cảnh hội thoại:
- Bạn được cung cấp lịch sử hội thoại gần nhất và có thể có mục 'TOUR ĐANG ĐƯỢC KHÁCH QUAN TÂM'.
- Nếu khách dùng các cụm như 'tour này', 'tour đó', 'tour vừa rồi', 'nó', 'mấy ngày', 'giá bao nhiêu', 'lịch khởi hành', 'còn chỗ không', 'đặt tour', hãy hiểu là khách đang hỏi về tour trong mục 'TOUR ĐANG ĐƯỢC KHÁCH QUAN TÂM'.
- Nếu đã có 'TOUR ĐANG ĐƯỢC KHÁCH QUAN TÂM', hãy trả lời trực tiếp dựa trên dữ liệu tour đó, KHÔNG hỏi lại tên tour.
- Chỉ hỏi lại khách đang quan tâm tour nào nếu không có 'TOUR ĐANG ĐƯỢC KHÁCH QUAN TÂM' và không xác định được tour từ ngữ cảnh.

Dữ liệu tour hiện có:
{$toursContext}

Mã giảm giá đang hoạt động:
{$promoContext}{$currentTourContext}";

            // 7. Lấy API key theo thứ tự ưu tiên: DB settings > config (không dùng env() trực tiếp)
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

            // 8. Xây dựng lịch sử hội thoại gần nhất để gửi cho AI
            $conversationMessages = $this->getRecentConversation();

            $apiMessages = [
                ['role' => 'system', 'content' => $systemPrompt],
            ];

            // Thêm lịch sử hội thoại gần nhất (tối đa 3 cặp = 6 messages)
            foreach ($conversationMessages as $msg) {
                $apiMessages[] = $msg;
            }

            // Thêm câu hỏi hiện tại
            $apiMessages[] = ['role' => 'user', 'content' => $question];

            // 9. Gọi OpenRouter API với timeout
            $response = Http::connectTimeout(10)->timeout(30)->withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name', 'TourTravel'),
            ])->post($endpoint, [
                'model' => $model,
                'messages' => $apiMessages,
                'temperature' => 0.7,
                'max_tokens' => 1500,
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

            // 10. Lưu vào lịch sử Chat
            if (auth()->check()) {
                // User đã đăng nhập: lưu vào database
                $chat = new ChatHistory();
                $chat->user_id = auth()->id();
                $chat->session_id = session()->getId();
                $chat->message = $question;
                $chat->reply = $answer;
                $chat->save();
            }

            // Luôn lưu vào session (cả guest và logged-in) để memory ngắn hạn
            $this->saveConversationToSession($question, $answer);

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

    /**
     * Phát hiện tour cụ thể từ câu hỏi và lưu vào session.
     * Match theo mã tour (#T0007, T0007) hoặc tên tour chính xác.
     */
    private function detectAndSaveCurrentTour(string $question, $tours): void
    {
        // 1. Match mã tour: #T0007, T0007
        if (preg_match('/#?T(\d{4})/i', $question, $matches)) {
            $tourId = (int) $matches[1];
            $matched = $tours->firstWhere('id', $tourId);
            if ($matched) {
                session(['chat_current_tour_id' => $matched->id]);
                return;
            }
        }

        // 2. Match tên tour chính xác hoặc gần đúng (chứa phần lớn tên tour)
        $questionLower = Str::lower($question);
        $bestMatch = null;
        $bestScore = 0;

        foreach ($tours as $tour) {
            $titleLower = Str::lower($tour->title);

            // Tên tour xuất hiện trong câu hỏi (khách paste hoặc gõ gần đủ tên)
            if (Str::contains($questionLower, $titleLower)) {
                session(['chat_current_tour_id' => $tour->id]);
                return;
            }

            // Câu hỏi xuất hiện trong tên tour (khách gõ tắt nhưng rõ ràng)
            if (Str::length($question) >= 10 && Str::contains($titleLower, $questionLower)) {
                session(['chat_current_tour_id' => $tour->id]);
                return;
            }

            // Đếm số từ khớp để tìm match tốt nhất
            $questionWords = array_filter(explode(' ', $questionLower), fn($w) => Str::length($w) >= 3);
            $matchedWords = 0;
            foreach ($questionWords as $word) {
                if (Str::contains($titleLower, $word)) {
                    $matchedWords++;
                }
            }

            // Yêu cầu ít nhất 3 từ khớp và chiếm >= 60% số từ trong câu hỏi
            if ($matchedWords >= 3 && count($questionWords) > 0 && ($matchedWords / count($questionWords)) >= 0.6) {
                if ($matchedWords > $bestScore) {
                    $bestScore = $matchedWords;
                    $bestMatch = $tour;
                }
            }
        }

        if ($bestMatch) {
            session(['chat_current_tour_id' => $bestMatch->id]);
        }

        // Nếu không match rõ ràng 1 tour → không thay đổi current tour
    }

    /**
     * Lấy lịch sử hội thoại gần nhất (tối đa 3 cặp Q&A).
     * Ưu tiên: DB (đã đăng nhập) > Session (guest).
     */
    private function getRecentConversation(): array
    {
        $messages = [];
        $limit = 3; // 3 cặp gần nhất

        if (auth()->check()) {
            // Lấy từ database
            $recentChats = ChatHistory::where('user_id', auth()->id())
                ->latest()
                ->limit($limit)
                ->get()
                ->reverse()
                ->values();

            foreach ($recentChats as $chat) {
                $messages[] = ['role' => 'user', 'content' => $chat->message];
                $messages[] = ['role' => 'assistant', 'content' => $chat->reply];
            }
        } else {
            // Lấy từ session cho guest
            $sessionHistory = session('chat_history', []);
            $recent = array_slice($sessionHistory, -$limit);

            foreach ($recent as $pair) {
                $messages[] = ['role' => 'user', 'content' => $pair['question']];
                $messages[] = ['role' => 'assistant', 'content' => $pair['answer']];
            }
        }

        return $messages;
    }

    /**
     * Lưu cặp Q&A vào session (cho cả guest và logged-in users).
     */
    private function saveConversationToSession(string $question, string $answer): void
    {
        $history = session('chat_history', []);
        $history[] = ['question' => $question, 'answer' => $answer];

        // Giữ tối đa 10 cặp gần nhất trong session
        if (count($history) > 10) {
            $history = array_slice($history, -10);
        }

        session(['chat_history' => $history]);
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
