<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function verify($ticket_code)
    {
        $passenger = Passenger::with(['booking.departure.tour', 'booking.user'])
            ->where('ticket_code', $ticket_code)->firstOrFail();

        return view('admin.tickets.verify', compact('passenger'));
    }

    public function checkIn(Request $request, $ticket_code)
    {
        $passenger = Passenger::where('ticket_code', $ticket_code)->firstOrFail();

        if ($passenger->checked_in_at) {
            return back()->with('error', 'Hành khách này đã check-in trước đó vào lúc ' . $passenger->checked_in_at->format('d/m/Y H:i:s'));
        }

        $passenger->checked_in_at = now();
        $passenger->save();

        return back()->with('success', 'Xác nhận Check-in thành công cho hành khách: ' . $passenger->name);
    }
}
