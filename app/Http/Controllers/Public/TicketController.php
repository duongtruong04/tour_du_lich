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
        if ($booking->user_id !== Auth::id()) {
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
