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
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('booking_code', 'like', '%' . $keyword . '%')
                  ->orWhereHas('user', function ($userQuery) use ($keyword) {
                      $userQuery->where('full_name', 'like', '%' . $keyword . '%')
                                ->orWhere('email', 'like', '%' . $keyword . '%')
                                ->orWhere('phone', 'like', '%' . $keyword . '%');
                  })
                  ->orWhereHas('departure.tour', function ($tourQuery) use ($keyword) {
                      $tourQuery->where('title', 'like', '%' . $keyword . '%');
                  });
            });
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

        if ($request->export == 'excel') {
            $bookings = $query->get();
            return view('admin.bookings.excel', compact('bookings'));
        }

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking, Request $request)
    {
        $booking->load(['user', 'departure.tour', 'passengers', 'payments']);

        if ($request->export == 'excel') {
            return view('admin.bookings.show_excel', compact('booking'));
        }

        return view('admin.bookings.show', compact('booking'));
    }

    public function printInvoice(Booking $booking)
    {
        $booking->load(['user', 'departure.tour', 'passengers', 'payments']);
        return view('admin.bookings.print_invoice', compact('booking'));
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
            'status' => 'nullable|in:Pending,Confirmed,Completed,Cancelled',
            'payment_status' => 'nullable|in:Unpaid,Paid',
            'notes' => 'nullable|string',
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
            'status' => $request->status ?? 'Confirmed',
            'payment_status' => $request->payment_status ?? 'Paid',
            'notes' => $request->notes ?? 'Đặt tại quầy (Tiền mặt)',
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

    public function destroy(Request $request, Booking $booking)
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

        // Redirect back preserving search/filter/pagination state
        $returnUrl = $request->input('return_url');
        if ($returnUrl && str_starts_with($returnUrl, url('/'))) {
            // Parse the return URL to check if the page is still valid
            $parsed = parse_url($returnUrl);
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $queryParams);
                if (isset($queryParams['page']) && (int)$queryParams['page'] > 1) {
                    // Rebuild the query to check how many records remain on this page
                    $checkQuery = Booking::with(['user', 'departure.tour']);
                    if (!empty($queryParams['search'])) {
                        $keyword = $queryParams['search'];
                        $checkQuery->where(function ($q) use ($keyword) {
                            $q->where('booking_code', 'like', '%' . $keyword . '%')
                              ->orWhereHas('user', function ($uq) use ($keyword) {
                                  $uq->where('full_name', 'like', '%' . $keyword . '%')
                                     ->orWhere('email', 'like', '%' . $keyword . '%')
                                     ->orWhere('phone', 'like', '%' . $keyword . '%');
                              })
                              ->orWhereHas('departure.tour', function ($tq) use ($keyword) {
                                  $tq->where('title', 'like', '%' . $keyword . '%');
                              });
                        });
                    }
                    if (!empty($queryParams['status'])) {
                        $checkQuery->where('status', $queryParams['status']);
                    }
                    if (!empty($queryParams['payment_status'])) {
                        $checkQuery->where('payment_status', $queryParams['payment_status']);
                    }
                    $totalRecords = $checkQuery->count();
                    $perPage = 10;
                    $lastPage = max(1, (int)ceil($totalRecords / $perPage));
                    $requestedPage = (int)$queryParams['page'];
                    if ($requestedPage > $lastPage) {
                        $queryParams['page'] = $lastPage;
                        $newQuery = http_build_query($queryParams);
                        $newUrl = strtok($returnUrl, '?') . '?' . $newQuery;
                        return redirect()->to($newUrl)->with('success', 'Xóa đơn hàng thành công.');
                    }
                }
            }
            return redirect()->to($returnUrl)->with('success', 'Xóa đơn hàng thành công.');
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Xóa đơn hàng thành công.');
    }
}
