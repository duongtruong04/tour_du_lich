<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsCategory::query();
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $categories = $query->latest()->paginate(10);
        return view('admin.news_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.news_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:news_categories']);
        NewsCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return redirect()->route('admin.news_categories.index')->with('success', 'Thêm chuyên mục thành công.');
    }

    public function edit(NewsCategory $newsCategory)
    {
        return view('admin.news_categories.edit', compact('newsCategory'));
    }

    public function update(Request $request, NewsCategory $newsCategory)
    {
        $request->validate(['name' => 'required|unique:news_categories,name,' . $newsCategory->id]);
        $newsCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return redirect()->route('admin.news_categories.index')->with('success', 'Cập nhật chuyên mục thành công.');
    }

    public function destroy(NewsCategory $newsCategory)
    {
        if ($newsCategory->news()->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa chuyên mục đang có bài viết.');
        }
        $newsCategory->delete();
        return redirect()->route('admin.news_categories.index')->with('success', 'Xóa chuyên mục thành công.');
    }
}
