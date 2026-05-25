@extends('layouts.admin')

@section('title', 'Chi tiết Tour')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-20">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.tours.index') }}" class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-teal-600 transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight uppercase line-clamp-1">{{ $tour->title }}</h1>
                <p class="text-[10px] text-teal-600 font-black uppercase tracking-widest mt-1">Hành trình: {{ $tour->duration }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.tours.edit', $tour) }}" class="px-6 py-3 bg-teal-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-500/20">
                <i class="fas fa-edit mr-2"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Image Gallery -->
            <div class="bg-white p-4 rounded-[2.5rem] shadow-sm border border-slate-50 overflow-hidden">
                <div class="grid grid-cols-4 gap-4 aspect-[16/9]" id="main-gallery">
                    <div class="col-span-3 rounded-[2rem] overflow-hidden shadow-inner relative bg-slate-100">
                        @php $primaryImage = $tour->images->where('is_primary', 1)->first() ?? $tour->images->first(); @endphp
                        @if($primaryImage)
                            <img src="{{ $primaryImage->image_url }}" id="active-image" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <i class="fas fa-image text-4xl"></i>
                            </div>
                        @endif
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-teal-950/60 p-10">
                            <h2 class="text-3xl font-black text-white tracking-tighter uppercase">{{ $tour->title }}</h2>
                            <p class="text-teal-200 text-xs font-bold uppercase tracking-widest mt-2">{{ $tour->summary }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 overflow-y-auto max-h-full pr-2 custom-scrollbar">
                        @foreach($tour->images as $img)
                        <div onclick="changeActiveImage('{{ $img->image_url }}')" class="aspect-square rounded-2xl overflow-hidden cursor-pointer hover:ring-4 hover:ring-teal-400 transition-all shadow-sm">
                            <img src="{{ $img->image_url }}" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Destinations Timeline -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-50">
                <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-10 flex items-center lg:ml-8">
                    <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3 scale-110">
                        <i class="fas fa-map-marked-alt"></i>
                    </span>
                    Điểm đến chi tiết
                </h2>
                
                <div class="space-y-12">
                    @foreach($tour->destinations as $dest)
                    <div class="flex gap-8 group">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-black group-hover:bg-teal-600 group-hover:text-white transition-all shadow-sm">
                                {{ $loop->iteration }}
                            </div>
                            @if(!$loop->last)
                                <div class="w-0.5 h-full bg-teal-50 group-hover:bg-teal-100 mt-2 transition-colors"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-10">
                            <div class="bg-slate-50 p-6 rounded-[2rem] border border-transparent hover:border-teal-100 hover:bg-white transition-all">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ $dest->name }}</h3>
                                    <span class="px-4 py-1.5 bg-white text-teal-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-teal-50 shadow-sm">{{ $dest->location }}</span>
                                </div>
                                @if($dest->image_path)
                                    <div class="w-full h-48 rounded-2xl overflow-hidden mb-4 shadow-inner">
                                        <img src="{{ $dest->image_url }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <p class="text-slate-500 text-sm font-medium leading-relaxed italic">"{{ $dest->description }}"</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-8">
            <!-- Price & Status -->
            <div class="bg-teal-900 text-white p-10 rounded-[2.5rem] shadow-xl shadow-teal-900/40 relative overflow-hidden">
                <i class="fas fa-ticket-alt absolute -right-4 -bottom-4 text-8xl opacity-10 rotate-12"></i>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-teal-400 uppercase tracking-[0.3em] mb-4">Giá từ</p>
                    <div class="text-5xl font-black tracking-tighter">{{ number_format($tour->base_price) }}<span class="text-xl ml-1 opacity-50 font-medium">đ</span></div>
                    <div class="h-2 w-12 bg-orange-500 rounded-full my-8"></div>
                    <div class="space-y-3">
                        <div class="flex items-center text-xs font-bold text-teal-200">
                             <i class="fas fa-clock w-6 opacity-50"></i> Hành trình: {{ $tour->duration }}
                        </div>
                        <div class="flex items-center text-xs font-bold text-teal-200">
                             <i class="fas fa-calendar-check w-6 opacity-50"></i> {{ $tour->departures->count() }} ngày khởi hành
                        </div>
                        <div class="flex items-center text-xs font-bold text-teal-200">
                             <i class="fas fa-eye w-6 opacity-50"></i> Trạng thái: 
                             <span class="ml-2 px-3 py-1 bg-white/10 rounded-full text-[10px] font-black uppercase tracking-widest {{ $tour->is_active ? 'text-teal-400' : 'text-rose-400' }}">
                                 {{ $tour->is_active ? 'Công khai' : 'Tạm ẩn' }}
                             </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Departures -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-50">
                <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-8 flex items-center">
                    <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    Ngày khởi hành
                </h2>
                <div class="space-y-4">
                    @forelse($tour->departures as $dep)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-teal-100 hover:bg-white transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ngày khởi hành</p>
                                <p class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $dep->start_date->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-teal-600 uppercase tracking-widest mb-1">Chỗ trống</p>
                                <p class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $dep->available_seats }}/{{ $dep->max_seats }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center py-6 text-slate-400 text-[10px] font-black uppercase tracking-widest italic">Chưa có lịch khởi hành.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function changeActiveImage(src) {
        document.getElementById('active-image').src = src;
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection
