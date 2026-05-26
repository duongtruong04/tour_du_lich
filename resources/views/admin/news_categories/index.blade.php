@extends('layouts.admin')

@section('title', 'Danh mục Tin tức')

@section('actions')
<a href="{{ route('admin.news_categories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center shadow-sm">
    <i class="fas fa-plus mr-2"></i> Thêm chuyên mục
</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-4 bg-gray-50 border-b border-gray-100">
            <form action="{{ route('admin.news_categories.index') }}" method="GET" class="flex space-x-2">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên chuyên mục..." 
                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-bold">Tìm</button>
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Tên chuyên mục</th>
                        <th class="px-6 py-4">Đường dẫn (Slug)</th>
                        <th class="px-6 py-4">Số bài viết</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-400 font-mono text-xs">#{{ $cat->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $cat->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $cat->slug }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-center">
                            <span class="bg-gray-100 px-3 py-1 rounded-full">{{ $cat->news()->count() }}</span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.news_categories.edit', $cat->id) }}" class="text-blue-600 hover:text-blue-800" title="Sửa"><i class="fas fa-edit"></i></a>
                            @if(Auth::user()->role_id == 1)
                            <form action="{{ route('admin.news_categories.destroy', $cat->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Xóa chuyên mục này?')" title="Xóa"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Chưa có chuyên mục bài viết nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $categories->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
