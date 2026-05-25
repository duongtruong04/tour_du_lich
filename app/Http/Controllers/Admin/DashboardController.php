<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tours' => Tour::count(),
            'total_bookings' => Booking::count(),
            'total_revenue' => Booking::where('payment_status', 'Paid')->sum('total_price'),
            'total_customers' => User::where('role_id', 2)->count(),
        ];

        $recent_bookings = Booking::with(['user', 'departure.tour'])->latest()->take(5)->get();
        $top_tours = Tour::withCount('bookings')->orderBy('bookings_count', 'desc')->take(5)->get();

        // Chart Data: Revenue by month
        $revenue_by_month = Booking::where('payment_status', 'Paid')
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings', 'top_tours', 'revenue_by_month'));
    }
}
