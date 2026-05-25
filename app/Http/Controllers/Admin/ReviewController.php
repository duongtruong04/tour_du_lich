<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'tour']);
        
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%');
            })->orWhereHas('tour', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->rating) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Xóa bình luận thành công.');
    }
}
