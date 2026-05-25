@extends('layouts.print')

@section('invoice_type', 'BÁO CÁO TIN TỨC & NỘI DUNG')

@section('content')
<div class="mb-6 text-sm text-gray-600 italic border-l-4 border-indigo-200 pl-4 py-2 bg-indigo-50/30">
    Danh sách các bài viết đang hiển thị trên hệ thống tin tức của Tour Du Lịch.
</div>

<table class="w-full text-left border-collapse border border-gray-200">
    <thead>
        <tr class="bg-indigo-50 text-indigo-900 text-[10px] uppercase font-bold text-center">
            <th class="px-3 py-3 border border-gray-200">ID</th>
            <th class="px-3 py-3 border border-gray-200 text-left">Tiêu đề bài viết</th>
            <th class="px-3 py-3 border border-gray-200">Chuyên mục</th>
            <th class="px-3 py-3 border border-gray-200">Tác giả</th>
            <th class="px-3 py-3 border border-gray-200">Ngày đăng</th>
            <th class="px-3 py-3 border border-gray-200">Lượt xem</th>
        </tr>
    </thead>
    <tbody class="text-sm text-gray-700">
        @foreach($news as $item)
        <tr>
            <td class="px-3 py-3 border border-gray-200 text-center font-mono text-xs">#{{ $item->id }}</td>
            <td class="px-3 py-3 border border-gray-200">
                <div class="font-bold text-gray-900 leading-tight">{{ $item->title }}</div>
                <div class="text-[9px] text-gray-400 mt-0.5 italic">{{ Str::limit($item->summary, 80) }}</div>
            </td>
            <td class="px-3 py-3 border border-gray-200 text-center uppercase text-[10px] font-bold text-indigo-600">
                {{ $item->category->name }}
            </td>
            <td class="px-3 py-3 border border-gray-200 text-center text-xs">
                {{ $item->author->full_name }}
            </td>
            <td class="px-3 py-3 border border-gray-200 text-center text-xs">
                {{ $item->created_at->format('d/m/Y') }}
            </td>
            <td class="px-3 py-3 border border-gray-200 text-center font-bold text-gray-900">
                {{ number_format($item->view_count) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-12 p-6 bg-gray-50 border rounded-xl">
    <h4 class="font-black text-indigo-900 mb-4 uppercase text-xs tracking-widest border-b pb-2">Tổng hợp nội dung:</h4>
    <div class="grid grid-cols-4 gap-4 text-center">
        <div>
            <div class="text-2xl font-black text-gray-900">{{ $news->count() }}</div>
            <div class="text-[9px] font-bold text-gray-400 uppercase">Tổng bài viết</div>
        </div>
        <div>
            <div class="text-2xl font-black text-indigo-700">{{ $news->sum('view_count') }}</div>
            <div class="text-[9px] font-bold text-gray-400 uppercase">Tổng lượt xem</div>
        </div>
        <div>
            <div class="text-2xl font-black text-green-600">{{ $news->groupBy('category_id')->count() }}</div>
            <div class="text-[9px] font-bold text-gray-400 uppercase">Số chuyên mục</div>
        </div>
        <div>
            <div class="text-2xl font-black text-gray-900">{{ now()->format('m/Y') }}</div>
            <div class="text-[9px] font-bold text-gray-400 uppercase">Kỳ báo cáo</div>
        </div>
    </div>
</div>

<div class="mt-20 flex justify-between px-10">
    <div class="text-center">
        <p class="font-black uppercase text-[10px] text-gray-400 mb-20 tracking-widest">Phê duyệt nội dung</p>
        <p class="font-bold border-t border-gray-200 pt-2 uppercase text-xs">Phòng truyền thông</p>
    </div>
    <div class="text-center">
        <p class="font-black uppercase text-[10px] text-gray-400 mb-20 tracking-widest">Người xuất báo cáo</p>
        <p class="font-bold border-t border-gray-200 pt-2 uppercase text-xs">Administrator</p>
    </div>
</div>
@endsection
