<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $query = Tour::with(['images', 'destinations', 'reviews', 'departures'])->where('is_active', 1);

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->destination) {
            $query->whereHas('destinations', function($q) use ($request) {
                $q->where('destinations.id', $request->destination);
            });
        }

        if ($request->min_price) {
            $query->where('base_price', '>=', $request->min_price);
        }
        
        if ($request->max_price) {
            $query->where('base_price', '<=', $request->max_price);
        }

        if ($request->transportation) {
            $query->where('transportation', $request->transportation);
        }

        if ($request->duration_type) {
            if ($request->duration_type == 'short') {
                $query->where('duration', 'not like', '%ngày%')->orWhere('duration', 'like', '%1-2 ngày%');
            } elseif ($request->duration_type == 'medium') {
                $query->where('duration', 'like', '%3-5 ngày%');
            } elseif ($request->duration_type == 'long') {
                $query->where('duration', 'like', '%6+%')
                      ->orWhere('duration', 'like', '%tuần%')
                      ->orWhere('duration', 'REGEXP', '[6-9] ngày');
            }
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $tours = $query->paginate(12)->withQueryString();
        $destinations = Destination::withCount('tours')->get();
        $flash_sale_tour = Tour::active()->where('base_price', '>', 0)->inRandomOrder()->first();

        return view('public.tours.index', compact('tours', 'destinations', 'flash_sale_tour'));
    }

    public function show(Tour $tour)
    {
        // Hidden tours must not be accessible from the public side
        if (!$tour->is_active) {
            abort(404);
        }

        $tour->load(['images', 'destinations', 'departures' => function($q) {
            $q->where('start_date', '>=', date('Y-m-d'))->where('available_seats', '>', 0);
        }, 'reviews.user']);

        $related_tours = Tour::where('id', '!=', $tour->id)
            ->whereHas('destinations', function($q) use ($tour) {
                $q->whereIn('destinations.id', $tour->destinations->pluck('id'));
            })
            ->active()
            ->take(4)
            ->get();

        return view('public.tours.show', compact('tour', 'related_tours'));
    }
}
