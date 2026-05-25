<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Destination;
use App\Models\News;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $tours = Tour::with('images')->active()->latest()->take(6)->get();
        $destinations = Destination::latest()->take(8)->get();
        $news = News::with('category')->latest()->take(3)->get();
        
        $special_offer = \App\Models\Promotion::whereNotNull('title')
            ->whereNotNull('image_path')
            ->where('expiry_date', '>=', now())
            ->latest()
            ->first();

        $settings = Setting::pluck('value', 'key');

        return view('welcome', compact('tours', 'destinations', 'news', 'settings', 'special_offer'));
    }
}
