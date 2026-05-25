<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['category', 'author']);

        if ($request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->author) {
            $query->where('author_id', $request->author);
        }

        $news = $query->latest()->paginate(9);
        $categories = NewsCategory::withCount('news')->get();
        $recommended_tour = \App\Models\Tour::active()->inRandomOrder()->first();

        return view('public.news.index', compact('news', 'categories', 'recommended_tour'));
    }

    public function show(News $news)
    {
        $news->increment('view_count');
        $related_news = News::where('id', '!=', $news->id)
            ->where('category_id', $news->category_id)
            ->take(3)
            ->get();

        $recommended_tours = \App\Models\Tour::active()->with('images')->take(2)->get();

        return view('public.news.show', compact('news', 'related_news', 'recommended_tours'));
    }
}
