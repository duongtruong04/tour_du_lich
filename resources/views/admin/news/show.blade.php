@extends('layouts.admin')

@section('title', 'Xem bài viết')

@section('actions')
<div class="flex space-x-2 no-print">
    <a href="{{ route('admin.news.edit', $news->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center shadow-sm">
        <i class="fas fa-edit mr-2"></i> Chỉnh sửa
    </a>
    <a href="{{ route('admin.news.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
        Quay lại
    </a>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($news->image_path)
        <div class="w-full h-80 overflow-hidden">
            <img src="{{ $news->image_path }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
        </div>
        @endif

        <div class="p-10">
            <div class="flex items-center space-x-4 mb-6">
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase">{{ $news->category->name }}</span>
                <span class="text-gray-400 text-sm italic">{{ $news->created_at->format('d/m/Y H:i') }}</span>
                <span class="text-gray-400 text-sm">• Tác giả: <span class="text-gray-700 font-bold">{{ $news->author->full_name }}</span></span>
            </div>

            <h1 class="text-4xl font-black text-gray-900 leading-tight mb-8">{{ $news->title }}</h1>

            @if($news->summary)
            <div class="bg-gray-50 p-6 rounded-xl border-l-4 border-indigo-500 mb-8 italic text-gray-600 leading-relaxed">
                {{ $news->summary }}
            </div>
            @endif

            <div class="prose max-w-none text-gray-700 leading-loose text-lg whitespace-pre-line">
                {{ $news->content }}
            </div>
        </div>
    </article>

    <div class="mt-8 flex justify-between items-center text-sm text-gray-400 font-medium px-4">
        <div>Mã bài viết: #NEWS-{{ $news->id }}</div>
        <div class="flex items-center space-x-4">
            <span><i class="far fa-eye mr-1"></i> {{ $news->view_count }} lượt xem</span>
            <button onclick="window.print()" class="text-indigo-600 hover:underline"><i class="fas fa-print mr-1"></i> In bài viết</button>
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, .topbar, .no-print, header, footer { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        body { background: white !important; }
        .shadow-sm { box-shadow: none !important; border: none !important; }
        .rounded-xl { border-radius: 0 !important; }
        .max-w-4xl { max-width: 100% !important; }
    }
</style>
@endsection
