<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Departure;
use App\Models\Passenger;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'departure.tour']);
        
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('booking_code', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->latest()->paginate(10);

        if ($request->export == 'pdf') {
            $bookings = $query->get();
            return view('admin.bookings.print', compact('bookings'));
        }

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'departure.tour', 'passengers', 'payments']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function create()
    {
        $tours = Tour::active()->with('departures')->get();
        return view('admin.bookings.create', compact('tours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'full_name' => 'required',
            'departure_id' => 'required',
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = User::create([
                'email' => $request->email,
                'full_name' => $request->full_name,
                'password' => Hash::make('password123'),
                'role_id' => 2, // Customer
                'status' => 1,
            ]);
        }

        $departure = Departure::findOrFail($request->departure_id);
        $totalPrice = ($departure->price_override ?? $departure->tour->base_price) * count($request->passengers);

        $booking = Booking::create([
            'booking_code' => 'BK' . strtoupper(Str::random(8)),
            'user_id' => $user->id,
            'departure_id' => $departure->id,
            'total_price' => $totalPrice,
            'status' => 'Confirmed',
            'payment_status' => 'Paid',
            'notes' => 'Đặt tại quầy (Tiền mặt)',
        ]);

        foreach ($request->passengers as $passengerData) {
            $booking->passengers()->create([
                'name' => $passengerData['name'],
                'id_card' => $passengerData['id_card'] ?? null,
            ]);
        }

        // Update available seats
        $departure->decrement('available_seats', count($request->passengers));

        return redirect()->route('admin.bookings.index')->with('success', 'Đặt tour tại quầy thành công.');
    }

    public function edit(Booking $booking)
    {
        $booking->load(['user', 'departure.tour']);
        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required',
            'payment_status' => 'required',
        ]);

        $booking->update($request->only(['status', 'payment_status', 'notes']));

        return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật đơn hàng thành công.');
    }

    public function destroy(Booking $booking)
    {
        // Revert seats if not cancelled previously
        if ($booking->status != 'Cancelled' && $booking->departure) {
            $booking->departure->increment('available_seats', $booking->passengers()->count());
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($booking) {
            // Delete payments and passengers associated with the booking
            $booking->payments()->delete();
            $booking->passengers()->delete();
            $booking->delete();
        });

        return redirect()->route('admin.bookings.index')->with('success', 'Xóa đơn hàng thành công.');
    }
}
