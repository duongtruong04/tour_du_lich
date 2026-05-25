@extends('layouts.app')

@section('title', 'Danh sách Tour du lịch')

@section('content')
<!-- Breadcrumb Header -->
<header class="breadcrumb-header relative bg-dark py-24 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" class="absolute inset-0 w-full h-full object-cover opacity-30">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
    <div class="container relative z-10 text-center">
        <nav class="flex justify-center items-center gap-3 text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Trang chủ</a>
            <i class="fas fa-chevron-right text-[8px] opacity-40"></i>
            <span class="text-white">Tour du lịch</span>
        </nav>
        <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter uppercase mb-6">Tất cả <span class="text-primary italic">Hành trình</span></h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-sm font-medium leading-relaxed">
            Khám phá hàng trăm tour du lịch trọn gói với lịch trình chuẩn mực, dịch vụ cao cấp và giá cả minh bạch nhất.
        </p>
    </div>
</header>

<!-- Main Content Area -->
<section class="py-16 bg-slate-50" x-data="{ 
    viewMode: localStorage.getItem('tourViewMode') || 'grid', 
    compareList: [],
    toggleCompare(id, title) {
        let index = this.compareList.findIndex(item => item.id === id);
        if(index === -1) {
            if(this.compareList.length >= 3) { alert('Bạn chỉ có thể so sánh tối đa 3 tour!'); return; }
            this.compareList.push({id, title});
        } else {
            this.compareList.splice(index, 1);
        }
    },
    init() {
        $watch('viewMode', val => localStorage.setItem('tourViewMode', val))
    }
}">
    <div class="container">
        <div class="flex flex-col lg:flex-row gap-10">
            
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-80 flex-shrink-0">
                <div class="space-y-8">
                    
                    <!-- Advanced Filter Box -->
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100">
                        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest border-l-4 border-primary pl-3 m-0">Bộ lọc tìm kiếm</h4>
                            @if(request()->except('page'))
                                <a href="{{ route('public.tours.index') }}" class="text-[10px] font-black text-rose-500 uppercase tracking-widest hover:underline">Đặt lại</a>
                            @endif
                        </div>

                        <!-- Active Filter Tags (if any) -->
                        @if(request()->except(['page', 'sort']))
                            <div class="mb-6 p-4 bg-slate-50 rounded-2xl space-y-2">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest m-0">Đang lọc theo:</p>
                                <div class="flex flex-wrap gap-2 pt-1">
                                    @if(request('search'))
                                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-xl text-[10px] font-bold text-slate-700 flex items-center gap-2 shadow-sm">
                                            Từ khóa: {{ request('search') }}
                                        </span>
                                    @endif
                                    @if(request('destination'))
                                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-xl text-[10px] font-bold text-slate-700 flex items-center gap-2 shadow-sm">
                                            Điểm đến ID: {{ request('destination') }}
                                        </span>
                                    @endif
                                    @if(request('duration_type'))
                                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-xl text-[10px] font-bold text-slate-700 flex items-center gap-2 shadow-sm">
                                            Thời lượng: {{ request('duration_type') }}
                                        </span>
                                    @endif
                                    @if(request('transportation'))
                                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-xl text-[10px] font-bold text-slate-700 flex items-center gap-2 shadow-sm">
                                            Phương tiện: {{ request('transportation') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <form action="{{ route('public.tours.index') }}" method="GET" class="space-y-8">
                            <!-- Keyword Search -->
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Tên hành trình</label>
                                <div class="relative group">
                                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors"></i>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nhập tên tour..." class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-xs font-bold focus:ring-2 focus:ring-primary transition-all">
                                </div>
                            </div>

                            <!-- Destination -->
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Điểm đến</label>
                                <div class="relative group">
                                    <select name="destination" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-slate-600 appearance-none focus:ring-2 focus:ring-primary transition-all cursor-pointer">
                                        <option value="">Tất cả địa danh</option>
                                        @foreach($destinations as $dest)
                                            <option value="{{ $dest->id }}" {{ request('destination') == $dest->id ? 'selected' : '' }}>{{ $dest->name }} ({{ $dest->tours_count ?? 0 }})</option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                                </div>
                            </div>

                            <!-- Duration -->
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Thời lượng</label>
                                <div class="grid grid-cols-1 gap-2">
                                    @foreach(['short' => '1 - 2 Ngày', 'medium' => '3 - 5 Ngày', 'long' => '6+ Ngày'] as $key => $label)
                                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-all border border-transparent has-[:checked]:border-primary has-[:checked]:bg-emerald-50">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="duration_type" value="{{ $key }}" {{ request('duration_type') == $key ? 'checked' : '' }} class="hidden">
                                            <span class="w-4 h-4 rounded-full border-2 border-slate-300 flex items-center justify-center peer-checked:border-primary">
                                                <span class="w-2 h-2 rounded-full bg-primary opacity-0 {{ request('duration_type') == $key ? 'opacity-100' : '' }}"></span>
                                            </span>
                                            <span class="text-xs font-bold text-slate-600">{{ $label }}</span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Khoảng giá (VNĐ)</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="relative group">
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Từ..." class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-xs font-bold focus:ring-2 focus:ring-primary transition-all">
                                    </div>
                                    <div class="relative group">
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Đến..." class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-xs font-bold focus:ring-2 focus:ring-primary transition-all">
                                    </div>
                                </div>
                            </div>

                            <!-- Transportation -->
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Phương tiện di chuyển</label>
                                <div class="relative group">
                                    <select name="transportation" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-slate-600 appearance-none focus:ring-2 focus:ring-primary transition-all cursor-pointer">
                                        <option value="">Tất cả phương tiện</option>
                                        <option value="Máy bay" {{ request('transportation') == 'Máy bay' ? 'selected' : '' }}>✈️ Máy bay</option>
                                        <option value="Du thuyền" {{ request('transportation') == 'Du thuyền' ? 'selected' : '' }}>🚢 Du thuyền</option>
                                        <option value="Xe du lịch chất lượng cao" {{ request('transportation') == 'Xe du lịch chất lượng cao' ? 'selected' : '' }}>🚌 Xe du lịch chất lượng cao</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4 space-y-3">
                                <button type="submit" class="btn btn-primary w-full py-4 rounded-2xl shadow-lg shadow-emerald-500/20 font-black text-xs uppercase tracking-widest">
                                    <i class="fas fa-filter mr-2"></i> Áp dụng bộ lọc
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Widget: Flash Sale / Giờ chót -->
                    @if($flash_sale_tour)
                    <div class="bg-gradient-to-br from-slate-900 to-dark p-8 rounded-[2.5rem] relative overflow-hidden group shadow-xl border border-slate-800">
                        <div class="absolute inset-0 bg-primary/10 opacity-30 group-hover:opacity-50 transition-opacity"></div>
                        <div class="absolute top-0 right-0 w-40 h-40 bg-primary/20 rounded-full blur-3xl -mr-20 -mt-20"></div>
                        <div class="relative z-10 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 bg-rose-500 text-white rounded-xl text-[9px] font-black uppercase tracking-widest animate-pulse">
                                    🔥 Flash Sale Giờ Chót
                                </span>
                                <span class="text-[10px] font-mono text-amber-400 font-bold">Còn 04:25:12</span>
                            </div>
                            <h5 class="text-white text-lg font-black tracking-tight leading-tight uppercase">{{ $flash_sale_tour->title }}</h5>
                            <p class="text-xs text-slate-400 font-medium line-clamp-2 m-0">{{ $flash_sale_tour->summary }}</p>
                            <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] text-slate-500 line-through m-0">{{ number_format($flash_sale_tour->base_price) }}đ</p>
                                    <p class="text-xl font-black text-primary m-0">{{ number_format($flash_sale_tour->base_price * 0.8) }}đ</p>
                                </div>
                                <a href="{{ route('public.tours.show', $flash_sale_tour->slug) }}" class="btn btn-primary !py-2.5 !px-5 rounded-xl !text-[10px]">Đặt ngay</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Widget: Hỗ trợ 24/7 & Cam kết chất lượng -->
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100 space-y-6">
                        <h5 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 border-l-4 border-primary pl-3 m-0">
                            <i class="fas fa-shield-alt text-primary"></i> Cam kết dịch vụ
                        </h5>
                        <div class="space-y-4 text-xs text-slate-600 font-medium">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-teal-50 text-primary flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 m-0">Khởi hành 100% đúng lịch</p>
                                    <p class="text-[11px] text-slate-500 m-0">Đảm bảo hoàn tiền nếu thay đổi lịch trình không báo trước.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-teal-50 text-primary flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 m-0">Bảo hiểm du lịch 100tr/vụ</p>
                                    <p class="text-[11px] text-slate-500 m-0">An tâm tận hưởng chuyến đi với gói bảo hiểm cao cấp nhất.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-teal-50 text-primary flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 m-0">Hỗ trợ khách hàng 24/7</p>
                                    <p class="text-[11px] text-slate-500 m-0">Hotline điều hành: <a href="tel:{{ str_replace(' ', '', $settings['contact_phone'] ?? '0123456789') }}" class="text-primary font-black">{{ $settings['contact_phone'] ?? '0123 456 789' }}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>

            <!-- Results Area -->
            <div class="flex-1 space-y-8">
                
                <!-- Toolbar: Count, View Mode & Sort -->
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest m-0">
                            Hiển thị <span class="text-slate-900 font-black">{{ $tours->count() }}</span> / <span class="text-slate-900 font-black">{{ $tours->total() }}</span> hành trình
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-6">
                        <!-- View Mode Toggle -->
                        <div class="flex items-center bg-slate-100 p-1 rounded-xl">
                            <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white text-primary shadow-sm font-bold' : 'text-slate-400'" class="px-4 py-2 rounded-lg text-xs transition-all flex items-center gap-2">
                                <i class="fas fa-th-large"></i> <span class="hidden sm:inline">Lưới</span>
                            </button>
                            <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white text-primary shadow-sm font-bold' : 'text-slate-400'" class="px-4 py-2 rounded-lg text-xs transition-all flex items-center gap-2">
                                <i class="fas fa-list"></i> <span class="hidden sm:inline">Danh sách</span>
                            </button>
                        </div>

                        <!-- Sort Form -->
                        <form action="{{ route('public.tours.index') }}" method="GET" class="flex items-center gap-3">
                            @foreach(request()->except('sort') as $key => $val)
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endforeach
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest m-0 hidden sm:block">Sắp xếp:</label>
                            <select name="sort" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl py-2 px-6 text-xs font-bold text-slate-600 cursor-pointer focus:ring-1 focus:ring-primary">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Giá: Thấp -> Cao</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá: Cao -> Thấp</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Đánh giá cao</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Empty State -->
                @if($tours->isEmpty())
                    <div class="text-center py-24 bg-white rounded-[3rem] shadow-sm border border-dashed border-slate-200 space-y-6">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-300">
                            <i class="fas fa-search-location text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tighter uppercase m-0">Không tìm thấy tour phù hợp</h3>
                        <p class="text-slate-500 font-medium max-w-md mx-auto m-0 leading-relaxed">
                            Không có hành trình nào khớp với tiêu chí tìm kiếm của bạn. Vui lòng thử xóa bộ lọc hoặc tìm kiếm với từ khóa khác.
                        </p>
                        <a href="{{ route('public.tours.index') }}" class="btn btn-primary inline-flex items-center gap-2">
                            <i class="fas fa-redo"></i> Xem tất cả tour
                        </a>
                    </div>
                @else
                    
                    <!-- GRID VIEW MODE -->
                    <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($tours as $tour)
                        <a href="{{ route('public.tours.show', $tour->slug) }}" class="group bg-white rounded-[3rem] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col h-full cursor-pointer">
                            <div class="relative aspect-[16/10] overflow-hidden">
                                @php $primaryImage = $tour->images->where('is_primary', 1)->first() ?? $tour->images->first(); @endphp
                                @if($primaryImage)
                                    <img src="{{ $primaryImage->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                        <i class="fas fa-image text-5xl"></i>
                                    </div>
                                @endif
                                
                                <div class="absolute top-6 left-6 flex flex-col gap-2">
                                    <span class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-2xl text-[9px] font-black text-primary uppercase tracking-widest shadow-sm">
                                        <i class="far fa-clock mr-1"></i> {{ $tour->duration }}
                                    </span>
                                </div>
                                <div class="absolute bottom-6 right-6">
                                    <div class="bg-dark/80 backdrop-blur-md px-4 py-2 rounded-2xl text-white text-[10px] font-black uppercase tracking-widest shadow-lg">
                                        Từ {{ number_format($tour->base_price) }}đ
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-8 flex-1 flex flex-col">
                                <div class="flex items-center justify-between gap-3 mb-4">
                                    <div class="flex items-center gap-2 overflow-hidden">
                                        <span class="w-6 h-[1px] bg-primary flex-shrink-0"></span>
                                        <p class="text-primary text-[9px] font-black uppercase tracking-widest truncate m-0">
                                            {{ $tour->destinations->pluck('name')->implode(' — ') }}
                                        </p>
                                    </div>
                                    <!-- Compare Button -->
                                    <button @click.prevent.stop="toggleCompare({{ $tour->id }}, '{{ addslashes($tour->title) }}')" class="text-slate-400 hover:text-primary transition-colors text-xs flex items-center gap-1 font-bold flex-shrink-0" title="So sánh tour">
                                        <i class="fas fa-balance-scale"></i> <span class="text-[9px] hidden xl:inline">So sánh</span>
                                    </button>
                                </div>

                                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter leading-tight mb-4 group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $tour->title }}
                                </h3>
                                <p class="text-slate-500 text-xs font-medium line-clamp-2 mb-6 italic leading-relaxed">
                                    {{ $tour->summary }}
                                </p>
                                
                                <!-- Tour Specs Grid -->
                                <div class="grid grid-cols-2 gap-3 mb-8 p-4 bg-slate-50 rounded-2xl text-[10px] text-slate-600 font-bold">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-bus text-amber-500 w-4"></i>
                                        <span class="truncate">{{ $tour->transportation ?? 'Xe du lịch cao cấp' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-rose-500 w-4"></i>
                                        <span>{{ $tour->destinations->count() }} điểm tham quan</span>
                                    </div>
                                    <div class="flex items-center gap-2 col-span-2 border-t border-slate-200 pt-3 mt-1 justify-between">
                                        <span class="flex items-center gap-1 text-amber-500 font-black">
                                            <i class="fas fa-star"></i> {{ number_format($tour->reviews->avg('rating') ?? 5.0, 1) }} ({{ $tour->reviews->count() }} đánh giá)
                                        </span>
                                        <span class="text-primary font-black text-[9px] uppercase tracking-widest">
                                            @php $available = $tour->departures->sum('available_seats'); @endphp
                                            {{ $available > 0 ? "Còn $available chỗ" : "Đang mở bán" }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-auto flex items-center justify-between pt-6 border-t border-slate-50">
                                    <div class="flex -space-x-3">
                                        <img src="https://ui-avatars.com/api/?name=User1&background=random" class="w-8 h-8 rounded-full border-2 border-white shadow-sm">
                                        <img src="https://ui-avatars.com/api/?name=User2&background=random" class="w-8 h-8 rounded-full border-2 border-white shadow-sm">
                                        <img src="https://ui-avatars.com/api/?name=User3&background=random" class="w-8 h-8 rounded-full border-2 border-white shadow-sm">
                                        <div class="w-8 h-8 rounded-full bg-slate-50 border-2 border-white flex items-center justify-center text-[8px] font-bold text-slate-400">+12</div>
                                    </div>
                                    <span class="flex items-center gap-3 text-[10px] font-black text-slate-900 uppercase tracking-widest group-hover:text-primary transition-colors">
                                        Xem chi tiết <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <!-- LIST / TABLE VIEW MODE -->
                    <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="space-y-4">
                        @foreach($tours as $tour)
                        <a href="{{ route('public.tours.show', $tour->slug) }}" class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl transition-all border border-slate-100 flex flex-col md:flex-row items-center gap-6 group cursor-pointer block">
                            <div class="w-full md:w-48 h-32 rounded-2xl overflow-hidden flex-shrink-0 relative">
                                @php $primaryImage = $tour->images->where('is_primary', 1)->first() ?? $tour->images->first(); @endphp
                                <img src="{{ $primaryImage ? $primaryImage->image_url : 'https://placehold.co/400x300' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <span class="absolute top-3 left-3 px-3 py-1 bg-dark/80 backdrop-blur-md rounded-xl text-[8px] font-black text-white uppercase tracking-widest">
                                    {{ $tour->duration }}
                                </span>
                            </div>

                            <div class="flex-1 space-y-2 w-full">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-[9px] font-black text-primary uppercase tracking-widest">{{ $tour->destinations->pluck('name')->implode(' — ') }}</span>
                                    <button @click.prevent.stop="toggleCompare({{ $tour->id }}, '{{ addslashes($tour->title) }}')" class="text-slate-400 hover:text-primary transition-colors text-xs flex items-center gap-1 font-bold" title="So sánh tour">
                                        <i class="fas fa-balance-scale"></i> <span class="text-[9px]">So sánh</span>
                                    </button>
                                </div>
                                <h4 class="text-lg font-black text-slate-900 uppercase tracking-tighter group-hover:text-primary transition-colors line-clamp-1 m-0">
                                    {{ $tour->title }}
                                </h4>
                                <p class="text-xs text-slate-500 line-clamp-1 m-0">{{ $tour->summary }}</p>
                                <div class="flex flex-wrap items-center gap-6 pt-2 text-[10px] text-slate-600 font-bold border-t border-slate-50">
                                    <span class="flex items-center gap-2"><i class="fas fa-bus text-amber-500"></i> {{ $tour->transportation ?? 'Xe cao cấp' }}</span>
                                    <span class="flex items-center gap-2"><i class="fas fa-star text-amber-400"></i> {{ number_format($tour->reviews->avg('rating') ?? 5.0, 1) }}</span>
                                    @php $available = $tour->departures->sum('available_seats'); @endphp
                                    <span class="flex items-center gap-2 text-primary"><i class="fas fa-user-check"></i> {{ $available > 0 ? "Còn $available chỗ" : "Đang mở bán" }}</span>
                                </div>
                            </div>

                            <div class="flex flex-row md:flex-col items-center justify-between md:justify-center gap-4 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6 w-full md:w-auto flex-shrink-0">
                                <div class="text-left md:text-right">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest m-0">Giá trọn gói từ</p>
                                    <p class="text-xl font-black text-primary m-0">{{ number_format($tour->base_price) }}đ</p>
                                </div>
                                <span class="btn btn-primary !py-3 !px-6 rounded-2xl !text-[10px]">
                                    Chi tiết <i class="fas fa-arrow-right ml-1"></i>
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-16 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex justify-center">
                        {{ $tours->appends(request()->query())->links() }}
                    </div>

                @endif

                <!-- FAQ Accordion Widget -->
                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 space-y-8 mt-16" x-data="{ activeFaq: null }">
                    <div class="text-center max-w-2xl mx-auto space-y-2">
                        <h4 class="text-2xl font-black text-slate-900 uppercase tracking-tighter m-0">Câu hỏi thường gặp (FAQ)</h4>
                        <p class="text-xs text-slate-500 m-0 leading-relaxed">Những thắc mắc phổ biến nhất của khách hàng khi lựa chọn và đặt tour tại {{ $settings['site_name'] ?? 'Tour Travel' }}.</p>
                    </div>

                    <div class="space-y-4 text-xs font-medium text-slate-600">
                        @php
                            $faqs = [
                                ['q' => 'Giá tour đã bao gồm những chi phí gì?', 'a' => 'Giá tour trọn gói tại Tour Travel đã bao gồm xe di chuyển chất lượng cao, khách sạn tiêu chuẩn (2-3 khách/phòng), các bữa ăn theo chương trình, vé tham quan các điểm, bảo hiểm du lịch và hướng dẫn viên suốt tuyến.'],
                                ['q' => 'Chính sách hoàn hủy tour được quy định như thế nào?', 'a' => 'Hủy trước 20 ngày khởi hành: Hoàn 100% tiền cọc. Hủy từ 10-19 ngày: Phạt 50% giá trị tour. Hủy từ 5-9 ngày: Phạt 75%. Hủy trong vòng 4 ngày khởi hành: Phạt 100% giá trị tour.'],
                                ['q' => 'Tôi có thể thay đổi ngày khởi hành sau khi đã đặt tour không?', 'a' => 'Bạn có thể chuyển đổi ngày khởi hành miễn phí trước 15 ngày khởi hành (tùy thuộc vào tình trạng chỗ trống của tour mới). Vui lòng liên hệ hotline để được hỗ trợ nhanh nhất.'],
                                ['q' => 'Công ty có hỗ trợ đón khách tận nơi không?', 'a' => 'Chúng tôi hỗ trợ đón khách tận nơi tại các khách sạn khu vực trung tâm thành phố hoặc tại điểm hẹn cố định của văn phòng công ty. Lịch trình đón chi tiết sẽ được gửi trước ngày đi 2 ngày.']
                            ];
                        @endphp
                        @foreach($faqs as $index => $faq)
                            <div class="border border-slate-100 rounded-2xl overflow-hidden transition-all duration-300" :class="activeFaq === {{ $index }} ? 'bg-slate-50 shadow-sm' : 'bg-white'">
                                <button @click="activeFaq = activeFaq === {{ $index }} ? null : {{ $index }}" class="w-full p-6 text-left font-black text-slate-800 uppercase tracking-tight flex items-center justify-between gap-4 focus:outline-none">
                                    <span>{{ $faq['q'] }}</span>
                                    <i class="fas fa-chevron-down text-primary transition-transform duration-300" :class="activeFaq === {{ $index }} ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="activeFaq === {{ $index }}" x-collapse>
                                    <div class="p-6 pt-0 text-slate-500 leading-relaxed border-t border-slate-100/50">
                                        {{ $faq['a'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Floating Compare Drawer -->
    <div x-show="compareList.length > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="fixed bottom-0 left-0 right-0 bg-dark/95 backdrop-blur-xl border-t border-white/10 p-6 z-50 shadow-2xl">
        <div class="container flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-primary/20 text-primary flex items-center justify-center text-xl border border-primary/30 flex-shrink-0">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div>
                    <h5 class="text-white font-black text-sm uppercase tracking-tight m-0">So sánh hành trình</h5>
                    <p class="text-[10px] text-slate-400 m-0">Đang chọn <span x-text="compareList.length" class="text-primary font-bold"></span>/3 tour</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 flex-1 max-w-xl justify-center md:justify-start">
                <template x-for="(item, index) in compareList" :key="item.id">
                    <div class="bg-white/10 border border-white/10 rounded-xl py-2 px-4 flex items-center gap-3 text-xs text-white max-w-[180px]">
                        <span class="truncate font-bold" x-text="item.title"></span>
                        <button @click="toggleCompare(item.id, item.title)" class="text-slate-400 hover:text-rose-500 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </template>
            </div>

            <div class="flex items-center gap-4 flex-shrink-0">
                <button @click="compareList = []" class="text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest transition-colors">Xóa tất cả</button>
                <button @click="alert('Tính năng so sánh chi tiết đang được mở rộng. Các tour đã chọn: ' + compareList.map(t => t.title).join(', '))" class="btn btn-primary !py-3 !px-8 rounded-2xl text-xs uppercase tracking-widest shadow-lg shadow-primary/20">
                    So sánh ngay <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
    </div>
</section>
@endsection
