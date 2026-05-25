<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['category', 'author']);
        
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $news = $query->latest()->paginate(10);
        $categories = NewsCategory::all();

        if ($request->export == 'pdf') {
            $news = $query->get();
            return view('admin.news.print', compact('news'));
        }

        return view('admin.news.index', compact('news', 'categories'));
    }

    public function show(News $news)
    {
        $news->load(['category', 'author']);
        return view('admin.news.show', compact('news'));
    }

    public function create()
    {
        $categories = NewsCategory::all();
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'author_id' => auth()->id() ?? 1,
            'content' => $request->input('content'),
            'summary' => $request->summary,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $data['image_path'] = $path;
        }

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'Đăng bài viết mới thành công.');
    }

    public function edit(News $news)
    {
        $categories = NewsCategory::all();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'content' => $request->input('content'),
            'summary' => $request->summary,
        ];

        if ($request->hasFile('image')) {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
            $path = $request->file('image')->store('news', 'public');
            $data['image_path'] = $path;
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Cập nhật bài viết thành công.');
    }

    public function destroy(News $news)
    {
        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Xóa bài viết thành công.');
    }
}
