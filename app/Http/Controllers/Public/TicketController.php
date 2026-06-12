<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function showTickets(Booking $booking)
    {
        $user = Auth::user();

        // Admin (role_id=1) và Nhân viên (role_id=3) được xem tất cả vé
        // Khách hàng chỉ xem được vé của chính mình
        if ((int) $booking->user_id != (int) $user->id && !in_array((int) $user->role_id, [1, 3])) {
            abort(403, 'Unauthorized access to tickets.');
        }

        if ($booking->payment_status !== 'Paid') {
            return redirect()->route('public.account.bookings.history')->with('error', 'Vui lòng thanh toán để xem vé điện tử.');
        }

        $booking->load(['passengers', 'departure.tour']);

        // Generate tickets on the fly for older paid bookings
        foreach ($booking->passengers as $passenger) {
            if (empty($passenger->ticket_code)) {
                $passenger->ticket_code = 'TK-' . strtoupper(\Illuminate\Support\Str::random(8));
                $passenger->save();
            }
        }

        return view('public.tickets.index', compact('booking'));
    }
}
