<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::withCount(['tours' => function($q) {
            $q->active();
        }])->get();
        
        return view('public.destinations.index', compact('destinations'));
    }

    public function show(Destination $destination)
    {
        $tours = $destination->tours()->active()->paginate(12);
        
        return view('public.destinations.show', compact('destination', 'tours'));
    }
}
