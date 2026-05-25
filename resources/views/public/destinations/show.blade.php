@extends('layouts.app')

@section('title', 'Tour du lịch tại ' . $destination->name)

@section('content')
<!-- Breadcrumb Header -->
<header class="breadcrumb-header min-h-[50vh] flex flex-col justify-center">
    @if($destination->image_path)
        <img src="{{ $destination->image_url }}" class="bg-image">
    @endif
    <div class="overlay"></div>
    <div class="container relative z-10">
        <nav class="breadcrumb-nav mb-8">
            <a href="{{ route('home') }}">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ route('public.destinations.index') }}">Điểm đến</a>
            <i class="fas fa-chevron-right"></i>
            <span class="text-white">{{ $destination->name }}</span>
        </nav>
        <div class="max-w-4xl">
            <p class="text-primary text-[10px] font-black uppercase tracking-[0.6em] mb-6 animate-fade-in-down">#KHÁM PHÁ ĐIỂM ĐẾN</p>
            <h1 class="text-6xl md:text-9xl font-black text-white tracking-tighter leading-none mb-8 uppercase animate-reveal">
                {{ $destination->name }}
            </h1>
            <div class="flex flex-wrap gap-8 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/20 backdrop-blur-md flex items-center justify-center text-primary border border-primary/20">
                        <i class="fas fa-map-marker-alt text-xs"></i>
                    </div>
                    <span class="text-[10px] font-black text-white uppercase tracking-widest">{{ $destination->location }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white border border-white/10">
                        <i class="fas fa-route text-xs"></i>
                    </div>
                    <span class="text-[10px] font-black text-white uppercase tracking-widest">{{ $tours->total() }} Hành trình</span>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Destination Highlights -->
<section class="py-24 bg-white">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
            <div class="space-y-12">
                <div class="space-y-4">
                    <h5 class="text-primary text-[10px] font-black uppercase tracking-[0.4em]">#DESTINATION HIGHLIGHTS</h5>
                    <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase leading-none">Tại sao nên<br>ghé thăm <span class="text-primary italic">{{ $destination->name }}?</span></h2>
                </div>
                <p class="text-slate-500 text-lg font-medium leading-relaxed italic">"{{ $destination->description }}"</p>
                
                <div class="grid grid-cols-2 gap-8">
                    <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 group hover:bg-primary transition-all duration-500">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-primary shadow-sm mb-6 group-hover:scale-110 transition-transform">
                            <i class="fas fa-camera-retro"></i>
                        </div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 group-hover:text-teal-100 transition-colors">Cảnh đẹp</h4>
                        <p class="text-xs font-black text-slate-800 uppercase tracking-tight group-hover:text-white transition-colors">Vô vàn góc check-in tuyệt mỹ</p>
                    </div>
                    <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 group hover:bg-primary transition-all duration-500">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-primary shadow-sm mb-6 group-hover:scale-110 transition-transform">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 group-hover:text-teal-100 transition-colors">Ẩm thực</h4>
                        <p class="text-xs font-black text-slate-800 uppercase tracking-tight group-hover:text-white transition-colors">Hương vị địa phương đặc sắc</p>
                    </div>
                </div>
            </div>
            @if($destination->image_path)
            <div class="relative">
                <div class="aspect-[4/5] rounded-[3rem] overflow-hidden shadow-2xl border border-slate-100">
                    <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-primary/10 rounded-[2rem] -z-10"></div>
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-primary/5 rounded-full -z-10"></div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Travel Guide Section -->
<section class="py-24 bg-dark relative overflow-hidden">
    <div class="absolute inset-0 bg-primary/5 opacity-40"></div>
    <div class="container relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
            <h5 class="text-primary text-[10px] font-black uppercase tracking-[0.6em]">#INSIDER GUIDE</h5>
            <h2 class="text-4xl md:text-6xl font-black text-white tracking-tighter uppercase leading-none">Cẩm nang <span class="bg-primary px-4 py-1 rounded-2xl">THÔNG MINH</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white/5 p-12 rounded-[3.5rem] border border-white/10 group hover:bg-white hover:border-white transition-all duration-500">
                <div class="w-16 h-16 rounded-2xl bg-primary flex items-center justify-center text-white mb-8 shadow-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-sun text-2xl"></i>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-4 group-hover:text-slate-900 transition-colors">Thời điểm lý tưởng</h4>
                <p class="text-xs font-medium text-slate-400 leading-relaxed group-hover:text-slate-600 transition-colors">Tháng 3 - Tháng 5 là khoảng thời gian đẹp nhất. Tiết trời mát mẻ, ít mưa và cây cối đâm chồi nảy lộc.</p>
            </div>
            <div class="bg-white/5 p-12 rounded-[3.5rem] border border-white/10 group hover:bg-white hover:border-white transition-all duration-500">
                <div class="w-16 h-16 rounded-2xl bg-amber-500 flex items-center justify-center text-white mb-8 shadow-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-bus-alt text-2xl"></i>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-4 group-hover:text-slate-900 transition-colors">Di chuyển tại đây</h4>
                <p class="text-xs font-medium text-slate-400 leading-relaxed group-hover:text-slate-600 transition-colors">Hệ thống xe giường nằm và xe limousine hiện đại kết nối các điểm du lịch chính một cách thuận tiện nhất.</p>
            </div>
            <div class="bg-white/5 p-12 rounded-[3.5rem] border border-white/10 group hover:bg-white hover:border-white transition-all duration-500">
                <div class="w-16 h-16 rounded-2xl bg-rose-500 flex items-center justify-center text-white mb-8 shadow-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-suitcase-rolling text-2xl"></i>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-4 group-hover:text-slate-900 transition-colors">Hành lý cần chuẩn bị</h4>
                <p class="text-xs font-medium text-slate-400 leading-relaxed group-hover:text-slate-600 transition-colors">Nên mang theo giày đi bộ thoải mái, kem chống nắng và một chiếc áo khoác nhẹ cho những buổi tối se lạnh.</p>
            </div>
        </div>
    </div>
</section>

<!-- Tours in Destination -->
<section class="py-24 bg-slate-50">
    <div class="container">
        <div class="flex items-center justify-between mb-16">
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Hành trình có sẵn <span class="text-primary italic">({{ $tours->total() }})</span></h2>
            <a href="{{ route('public.tours.index', ['destination' => $destination->id]) }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-primary transition-colors">Xem toàn bộ</a>
        </div>

        @if($tours->isEmpty())
            <div class="text-center py-24 bg-white rounded-[3rem] shadow-sm border border-dashed border-slate-200">
                <i class="fas fa-route text-4xl text-slate-200 mb-6"></i>
                <h3 class="text-xl font-bold text-slate-800 uppercase tracking-tight">Hiện chưa có tour cho địa điểm này</h3>
                <p class="text-slate-400 mt-2">Vui lòng quay lại sau.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                @foreach($tours as $tour)
                <div class="group bg-white rounded-[2.5rem] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col">
                    <div class="relative aspect-video overflow-hidden">
                        @php $primaryImage = $tour->images->first(); @endphp
                        <img src="{{ $primaryImage ? $primaryImage->image_url : 'https://placehold.co/800x600' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-6 left-6 px-3 py-1 bg-white/90 backdrop-blur-md rounded-xl text-[8px] font-black text-primary uppercase tracking-widest">
                            {{ $tour->duration }}
                        </div>
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                         <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter leading-tight mb-4 group-hover:text-primary transition-colors line-clamp-2">
                            {{ $tour->title }}
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-2 mb-6 p-4 bg-slate-50 rounded-2xl text-[9px] text-slate-600 font-bold">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-bus text-amber-500"></i>
                                <span class="truncate">{{ $tour->transportation ?? 'Xe du lịch cao cấp' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-star text-amber-400"></i>
                                <span>{{ number_format($tour->reviews->avg('rating') ?? 5.0, 1) }} / 5.0</span>
                            </div>
                        </div>

                        <div class="mt-auto flex justify-between items-center pt-6 border-t border-slate-50">
                            <p class="text-sm font-black text-slate-900">{{ number_format($tour->base_price) }}đ</p>
                            <a href="{{ route('public.tours.show', $tour->slug) }}" class="btn btn-primary !p-2 rounded-lg"><i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-16">
                {{ $tours->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
