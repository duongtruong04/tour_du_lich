@extends('layouts.admin')

@section('title', 'Quản lý Tin tức')

@section('content')
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/30">
        <form action="{{ route('admin.news.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Tìm tên bài viết..." 
                    class="pl-12 pr-4 py-3 bg-white border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 shadow-sm w-full md:w-64 font-medium text-sm">
            </div>
            <select name="category_id" class="px-4 py-3 bg-white border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 shadow-sm font-medium text-sm">
                <option value="">Tất cả chuyên mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-500/20">Lọc</button>
        </form>
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('admin.news.index', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all flex-1 md:flex-none text-center">
                <i class="fas fa-file-pdf mr-2"></i> Xuất PDF
            </a>
            <a href="{{ route('admin.news.create') }}" class="px-6 py-3 bg-teal-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-500/20 flex-1 md:flex-none text-center">
                <i class="fas fa-plus mr-2"></i> Đăng bài viết
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-slate-400 text-[10px] uppercase font-black border-b border-gray-50">
                    <th class="px-8 py-6">Bài viết</th>
                    <th class="px-8 py-6">Chuyên mục</th>
                    <th class="px-8 py-6">Tác giả</th>
                    <th class="px-8 py-6">Ngày đăng</th>
                    <th class="px-8 py-6 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs font-bold">
                @forelse($news as $item)
                <tr class="hover:bg-teal-50/30 transition-colors group">
                    <td class="px-8 py-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-12 rounded-xl bg-slate-100 overflow-hidden shadow-sm flex-shrink-0">
                                @if($item->image_path)
                                    <img src="{{ $item->image_url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                         <i class="fas fa-newspaper"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-slate-900 font-black uppercase tracking-tight line-clamp-1 max-w-xs">{{ $item->title }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">ID: #{{ $item->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-4">
                        <span class="px-3 py-1 bg-teal-50 text-teal-600 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $item->category->name }}</span>
                    </td>
                    <td class="px-8 py-4 text-slate-600 uppercase tracking-tighter">{{ $item->author->full_name }}</td>
                    <td class="px-8 py-4 text-slate-400 font-medium italic">{{ $item->created_at->format('d/m/Y') }}</td>
                    <td class="px-8 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.news.edit', $item) }}" class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(Auth::user()->role_id == 1)
                            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" onsubmit="return confirm('Xác nhận xóa bài viết này?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold italic uppercase text-[10px] tracking-widest">Không tìm thấy bài viết nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-8 border-t border-gray-50 bg-gray-50/30">
        {{ $news->links() }}
    </div>
</div>
@endsection
