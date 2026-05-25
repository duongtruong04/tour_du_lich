@extends('layouts.admin')

@section('title', 'Chi tiết Điểm đến')

@section('actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.destinations.edit', $destination->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center shadow-sm">
        <i class="fas fa-edit mr-2"></i> Chỉnh sửa
    </a>
    <a href="{{ route('admin.destinations.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
        Quay lại
    </a>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-2 text-indigo-600 font-bold uppercase text-xs mb-4">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $destination->location }}</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $destination->name }}</h1>
            
            <div class="prose max-w-none text-gray-600 leading-relaxed">
                <p class="whitespace-pre-line">{{ $destination->description }}</p>
            </div>
        </div>

        <!-- Tours related to this destination -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-route mr-2 text-indigo-500"></i> Các Tour đi qua điểm này
            </h3>
            <div class="divide-y divide-gray-100">
                @forelse($destination->tours as $tour)
                <div class="py-3 flex justify-between items-center">
                    <div>
                        <div class="font-medium text-gray-900">{{ $tour->title }}</div>
                        <div class="text-xs text-gray-500">{{ $tour->duration }} | {{ number_format($tour->base_price) }}đ</div>
                    </div>
                    <a href="{{ route('admin.tours.show', $tour->id) }}" class="text-indigo-600 hover:underline text-sm">Chi tiết</a>
                </div>
                @empty
                <p class="text-sm text-gray-400 py-4">Chưa có tour nào đi qua điểm đến này.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-indigo-900 text-white p-6 rounded-xl shadow-lg">
            <h3 class="font-bold mb-4">Thông tin nhanh</h3>
            <ul class="space-y-4 text-sm">
                <li class="flex justify-between border-b border-indigo-800 pb-2">
                    <span class="text-indigo-300">ID Dự án:</span>
                    <span>#{{ $destination->id }}</span>
                </li>
                <li class="flex justify-between border-b border-indigo-800 pb-2">
                    <span class="text-indigo-300">Vị trí:</span>
                    <span>{{ $destination->location }}</span>
                </li>
                <li class="flex justify-between border-b border-indigo-800 pb-2">
                    <span class="text-indigo-300">Ngày tạo:</span>
                    <span>{{ $destination->created_at->format('d/m/Y') }}</span>
                </li>
            </ul>
        </div>

        @if($destination->image_path)
        <div class="bg-white p-2 rounded-xl border shadow-sm">
            <img src="{{ $destination->image_url }}" 
                alt="{{ $destination->name }}" class="w-full h-48 object-cover rounded-lg">
        </div>
        @endif
    </div>
</div>
@endsection
