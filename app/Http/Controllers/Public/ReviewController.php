<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Tour $tour)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ]);

        // Check if user has already reviewed this tour
        $existing = Review::where('tour_id', $tour->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Bạn đã đánh giá hành trình này rồi.');
        }

        // Check if user has completed a booking for this tour (Optional but recommended)
        $hasBooked = Booking::where('user_id', Auth::id())
            ->whereHas('departure', function($q) use ($tour) {
                $q->where('tour_id', $tour->id);
            })
            ->where('status', 'Completed')
            ->exists();

        if (!$hasBooked) {
             return back()->with('error', 'Chỉ những khách hàng đã hoàn thành tour mới có thể đánh giá.');
        }

        Review::create([
            'tour_id' => $tour->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_hidden' => false,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã chia sẻ trải nghiệm!');
    }
}
