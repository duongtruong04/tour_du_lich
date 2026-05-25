<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Departure;
use App\Models\Passenger;
use App\Models\Promotion;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // ===================== VNPAY CONFIG (hardcode) =====================
    private string $VNPAY_TMN_CODE = 'YQA98Q8W';
    private string $VNPAY_HASH_SECRET = 'G3S771Y67RMYPYIOT2HBCFEHUVJ98ACH';
    private string $VNPAY_URL = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    // ==================================================================

    public function checkout(Request $request, Departure $departure)
    {
        $tour = $departure->tour;
        $promotions = Promotion::where('expiry_date', '>=', date('Y-m-d'))
            ->where(function($q) {
                $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit');
            })->get();
        return view('public.bookings.checkout', compact('departure', 'tour', 'promotions'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'departure_id' => 'required|exists:departures,id',
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string|max:100',
            'passengers.*.id_card' => 'nullable|string|max:20',
            'payment_method' => 'required|in:VNPay,MoMo,Cash',
        ]);

        $departure = Departure::with('tour')->findOrFail($request->departure_id);
        
        if ($departure->available_seats < count($request->passengers)) {
            return back()->with('error', 'Không đủ chỗ còn trống cho ngày này.');
        }

        DB::beginTransaction();
        try {
            $total_price = count($request->passengers) * ($departure->price_override ?? $departure->tour->base_price);
            
            // Check for promotion
            if ($request->promo_code) {
                $promo = Promotion::where('code', $request->promo_code)
                    ->where('expiry_date', '>=', date('Y-m-d'))
                    ->where(function($q) {
                        $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit');
                    })->first();

                if ($promo) {
                    if ($promo->discount_type == 'Fixed') {
                        $total_price -= $promo->discount_value;
                    } else {
                        $total_price -= ($total_price * $promo->discount_value / 100);
                    }
                    $promo->increment('used_count');
                }
            }

            $booking = Booking::create([
                'booking_code' => 'BK' . strtoupper(Str::random(10)),
                'user_id' => Auth::id(),
                'departure_id' => $departure->id,
                'total_price' => max(0, $total_price),
                'status' => 'Pending',
                'payment_status' => 'Unpaid',
                'notes' => $request->notes,
            ]);

            foreach ($request->passengers as $p) {
                $booking->passengers()->create([
                    'name' => $p['name'],
                    'id_card' => $p['id_card'] ?? null,
                ]);
            }

            $departure->decrement('available_seats', count($request->passengers));

            DB::commit();

            if ($request->payment_method === 'VNPay') {
                return $this->redirectToVnpay($booking);
            }

            return redirect()->route('public.bookings.success', $booking->booking_code)
                ->with('success', 'Đã lưu yêu cầu đặt tour. Vui lòng thanh toán.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function success($code)
    {
        $booking = Booking::with(['departure.tour', 'passengers'])->where('booking_code', $code)->firstOrFail();
        return view('public.bookings.success', compact('booking'));
    }

    public function history(Request $request)
    {
        $query = Booking::with(['departure.tour.images', 'departure', 'passengers'])
            ->where('user_id', Auth::id());

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(8)->withQueryString();
        return view('public.bookings.history', compact('bookings'));
    }

    private function redirectToVnpay(Booking $booking)
    {
        $tmnCode = $this->VNPAY_TMN_CODE;
        $hashSecret = $this->VNPAY_HASH_SECRET;
        $vnpUrl = $this->VNPAY_URL;
        $returnUrl = route('public.bookings.vnpayReturn');

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $tmnCode,
            "vnp_Amount" => (int)($booking->total_price * 100),
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toan don dat tour #" . $booking->booking_code,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $returnUrl,
            "vnp_TxnRef" => (string)$booking->booking_code,
        ];

        ksort($inputData);

        // Build giống CodeIgniter (chuẩn VNPAY): urlencode key/value theo thứ tự sort
        $query = "";
        $i = 0;
        $hashdata = "";

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $hashSecret);
        $paymentUrl = $vnpUrl . "?" . $query . "vnp_SecureHash=" . $vnpSecureHash;

        return redirect($paymentUrl);
    }

    public function vnpayReturn(Request $request)
    {
        $hashSecret = $this->VNPAY_HASH_SECRET;

        $vnpData = $request->all();
        $secureHash = $vnpData['vnp_SecureHash'] ?? null;

        unset($vnpData['vnp_SecureHash'], $vnpData['vnp_SecureHashType']);

        ksort($vnpData);

        // Build giống CodeIgniter (chuẩn VNPAY)
        $i = 0;
        $hashdata = "";
        foreach ($vnpData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $checkHash = hash_hmac('sha512', $hashdata, $hashSecret);

        if (!$secureHash || $checkHash !== $secureHash) {
            return redirect()->route('public.account.bookings.history')->with('error', 'Sai chữ ký VNPAY');
        }

        $bookingCode = $request->get('vnp_TxnRef');
        $responseCode = $request->get('vnp_ResponseCode');

        $booking = Booking::where('booking_code', $bookingCode)->first();
        if (!$booking) {
            return redirect()->route('public.account.bookings.history')->with('error', 'Không tìm thấy đơn đặt tour');
        }

        // Success
        if ($responseCode === '00') {
            $booking->payment_status = 'Paid';
            $booking->status = 'Confirmed';
            $booking->save();

            // Save to Payment table
            Payment::create([
                'booking_id' => $booking->id,
                'method' => 'VNPay',
                'amount' => $booking->total_price,
                'transaction_id' => $bookingCode,
                'payment_date' => now()
            ]);

            $this->generateTickets($booking);

            return redirect()->route('public.bookings.success', $booking->booking_code)->with('success', 'Thanh toán VNPAY thành công!');
        }

        // Fail / cancel
        $booking->payment_status = 'Unpaid';
        $booking->status = 'Cancelled';
        $booking->save();

        return redirect()->route('public.account.bookings.history')->with('error', 'Thanh toán VNPAY thất bại hoặc đã bị hủy!');
    }

    public function confirmTransfer($code)
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();
        
        if ($booking->payment_status !== 'Paid') {
            $booking->payment_status = 'Paid';
            $booking->status = 'Confirmed';
            $booking->save();

            Payment::create([
                'booking_id' => $booking->id,
                'method' => 'Cash', // Using 'Cash' or 'BankTransfer' depending on ENUM. In the form it uses 'Cash'.
                'amount' => $booking->total_price,
                'transaction_id' => 'BANK_TRANSFER_' . $booking->booking_code . '_' . time(),
                'payment_date' => now()
            ]);

            $this->generateTickets($booking);
        }

        return redirect()->route('public.account.bookings.history')->with('success', 'Đã xác nhận thanh toán chuyển khoản thành công!');
    }

    private function generateTickets(Booking $booking)
    {
        foreach ($booking->passengers as $passenger) {
            if (!$passenger->ticket_code) {
                $passenger->ticket_code = 'TK-' . strtoupper(Str::random(8));
                $passenger->save();
            }
        }
    }
}
