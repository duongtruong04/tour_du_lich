<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.departure.tour']);

        if ($request->search) {
            $query->where('transaction_id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('booking.user', function($q) use ($request) {
                      $q->where('full_name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->method) {
            $query->where('method', $request->method);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('payment_date', [$request->start_date, $request->end_date]);
        }

        $payments = $query->latest()->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.departure.tour', 'booking.passengers']);
        return view('admin.payments.show', compact('payment'));
    }
}
