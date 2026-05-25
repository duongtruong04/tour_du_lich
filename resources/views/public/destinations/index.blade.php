@extends('layouts.app')

@section('title', 'Khám phá điểm đến du lịch')

@section('content')
<!-- Breadcrumb Header -->
<header class="breadcrumb-header relative bg-dark py-24 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1528127269322-539801943592?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" class="absolute inset-0 w-full h-full object-cover opacity-30">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
    <div class="container relative z-10 text-center space-y-6">
        <nav class="flex justify-center items-center gap-3 text-xs font-bold uppercase tracking-widest text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Trang chủ</a>
            <i class="fas fa-chevron-right text-[8px] opacity-40"></i>
            <span class="text-white">Điểm đến</span>
        </nav>
        <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter uppercase m-0">Khám phá <span class="text-primary italic">Thế giới</span></h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-sm font-medium leading-relaxed m-0">
            Mỗi vùng đất là một câu chuyện lịch sử, văn hóa và cảnh sắc thiên nhiên tuyệt diệu đang chờ đón dấu chân khám phá của bạn.
        </p>
    </div>
</header>

<!-- Main Destination Explorer Section -->
<section class="py-16 bg-slate-50" x-data="{ 
    activeRegion: 'all', 
    searchQuery: '',
    filteredCount: {{ $destinations->count() }},
    updateCount() {
        setTimeout(() => {
            let visible = document.querySelectorAll('.destination-card:not([style*=\'display: none\'])');
            this.filteredCount = visible.length;
        }, 50);
    }
}">
    <div class="container space-y-12">
        
        <!-- Interactive Filter & Search Bar -->
        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6 relative">
            <!-- Region Tabs -->
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
                <button @click="activeRegion = 'all'; updateCount()" :class="activeRegion === 'all' ? 'bg-primary text-white shadow-lg shadow-primary/20 font-bold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'" class="px-6 py-3 rounded-2xl text-xs uppercase tracking-widest transition-all whitespace-nowrap">
                    Tất cả ({{ $destinations->count() }})
                </button>
                @foreach(['Miền Bắc', 'Miền Trung', 'Miền Nam'] as $region)
                    @php $count = $destinations->where('region', $region)->count(); @endphp
                    @if($count > 0)
                        <button @click="activeRegion = '{{ $region }}'; updateCount()" :class="activeRegion === '{{ $region }}' ? 'bg-primary text-white shadow-lg shadow-primary/20 font-bold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'" class="px-6 py-3 rounded-2xl text-xs uppercase tracking-widest transition-all whitespace-nowrap">
                            {{ $region }} ({{ $count }})
                        </button>
                    @endif
                @endforeach
            </div>

            <!-- Live Search Input -->
            <div class="relative w-full md:w-80 group">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors"></i>
                <input type="text" x-model="searchQuery" @input="updateCount()" placeholder="Tìm nhanh điểm đến..." class="w-full bg-slate-50 border-none rounded-2xl py-3.5 pl-12 pr-10 text-xs font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-primary transition-all">
                <button x-show="searchQuery" @click="searchQuery = ''; updateCount()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 text-xs">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Quick Stats Banner -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div>
                    <p class="text-xl font-black text-slate-900 m-0">{{ $destinations->count() }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest m-0">Điểm đến hấp dẫn</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fas fa-route"></i>
                </div>
                <div>
                    <p class="text-xl font-black text-slate-900 m-0">{{ $destinations->sum('tours_count') }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest m-0">Hành trình mở bán</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fas fa-cloud-sun"></i>
                </div>
                <div>
                    <p class="text-xl font-black text-slate-900 m-0">12 Tháng</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest m-0">Mùa đẹp quanh năm</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <p class="text-xl font-black text-slate-900 m-0">4.9 / 5.0</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest m-0">Đánh giá hài lòng</p>
                </div>
            </div>
        </div>

        <!-- Destination Results Grid -->
        <div class="space-y-6">
            <div class="flex items-center justify-between px-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest m-0">
                    Hiển thị <span x-text="filteredCount" class="text-slate-900 font-black"></span> địa danh phù hợp
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($destinations as $dest)
                <div x-show="(activeRegion === 'all' || activeRegion === '{{ $dest->region }}') && ('{{ Str::lower($dest->name) }}'.includes(searchQuery.toLowerCase()) || '{{ Str::lower($dest->location) }}'.includes(searchQuery.toLowerCase()))" 
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" 
                     @click="window.location.href='{{ route('public.destinations.show', $dest->id) }}'"
                     class="destination-card group relative aspect-[4/5] rounded-[3.5rem] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-700 bg-white border border-slate-100 flex flex-col justify-end cursor-pointer">
                    
                    @if($dest->image_path)
                        <img src="{{ $dest->image_url }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                    @else
                        <div class="absolute inset-0 bg-slate-100 flex items-center justify-center text-slate-300">
                            <i class="fas fa-map-marker-alt text-7xl"></i>
                        </div>
                    @endif
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                    
                    <!-- Content Box -->
                    <div class="relative z-10 p-10 space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <span class="px-4 py-2 bg-primary/20 backdrop-blur-md rounded-2xl text-[9px] font-black text-primary uppercase tracking-widest border border-primary/20 shadow-sm">
                                {{ $dest->tours_count }} Hành trình
                            </span>
                            <span class="px-4 py-2 bg-white/20 backdrop-blur-md rounded-2xl text-[9px] font-black text-white uppercase tracking-widest border border-white/20 shadow-sm opacity-90 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-sun text-amber-300 mr-1"></i> Quanh năm
                            </span>
                        </div>

                        <div>
                            <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">{{ $dest->location }}</p>
                            <h3 class="text-3xl font-black text-white uppercase tracking-tighter leading-none m-0">{{ $dest->name }}</h3>
                        </div>

                        <p class="text-slate-300 text-xs font-medium line-clamp-2 tracking-tight m-0 leading-relaxed opacity-0 group-hover:opacity-100 transition-all transform translate-y-4 group-hover:translate-y-0 duration-500">
                            {{ $dest->description }}
                        </p>

                        <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                            <div class="text-[10px] font-bold text-teal-300 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-compass"></i> Di sản & Văn hóa
                            </div>
                            <a href="{{ route('public.tours.index', ['destination' => $dest->id]) }}" @click.stop class="btn btn-primary !py-2.5 !px-5 rounded-xl !text-[10px] uppercase tracking-widest shadow-lg shadow-primary/20">
                                Xem tour <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Top Right Quick Badge -->
                    <div class="absolute top-8 right-8 w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/10 opacity-0 group-hover:opacity-100 transition-all duration-500 scale-50 group-hover:scale-100 pointer-events-none z-10 shadow-xl">
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- No Results State -->
            <div x-show="filteredCount === 0" class="text-center py-24 bg-white rounded-[3rem] shadow-sm border border-dashed border-slate-200 space-y-6">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-300">
                    <i class="fas fa-map-signs text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tighter uppercase m-0">Không tìm thấy địa điểm phù hợp</h3>
                <p class="text-slate-500 font-medium max-w-md mx-auto m-0 leading-relaxed">
                    Không có điểm đến nào khớp với từ khóa tìm kiếm của bạn. Vui lòng thử lại với tên tỉnh thành hoặc địa danh khác.
                </p>
                <button @click="searchQuery = ''; activeRegion = 'all'; updateCount()" class="btn btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-redo"></i> Đặt lại tìm kiếm
                </button>
            </div>
        </div>

        <!-- Widget: Bảng Thời tiết & Mùa du lịch lý tưởng -->
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 space-y-8 mt-16">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <h4 class="text-2xl font-black text-slate-900 uppercase tracking-tighter m-0">Bảng theo dõi thời tiết & Mùa du lịch</h4>
                    <p class="text-xs text-slate-500 m-0 mt-1">Thông tin tham khảo thời điểm đẹp nhất để lên kế hoạch cho chuyến đi của bạn.</p>
                </div>
                <span class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-2xl text-[10px] font-black text-slate-600 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-sync-alt text-primary animate-spin"></i> Cập nhật liên tục
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                            <th class="py-4 px-6 rounded-l-2xl">Khu vực / Điểm đến</th>
                            <th class="py-4 px-6">Thời gian lý tưởng</th>
                            <th class="py-4 px-6">Nhiệt độ trung bình</th>
                            <th class="py-4 px-6">Đặc trưng trải nghiệm</th>
                            <th class="py-4 px-6 rounded-r-2xl text-right">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-bold text-slate-600 divide-y divide-slate-50">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-5 px-6 font-black text-slate-900 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center"><i class="fas fa-mountain"></i></div>
                                Tây Bắc (Sapa, Hà Giang)
                            </td>
                            <td class="py-5 px-6 text-primary">Tháng 9 - Tháng 4</td>
                            <td class="py-5 px-6">15°C - 22°C (Se lạnh / Mát mẻ)</td>
                            <td class="py-5 px-6 text-slate-500 font-medium">Mùa lúa chín vàng, hoa tam giác mạch, săn mây đỉnh đèo.</td>
                            <td class="py-5 px-6 text-right"><span class="px-3 py-1 bg-emerald-50 text-primary border border-emerald-200 rounded-xl text-[9px] uppercase tracking-widest font-black">Mùa đẹp nhất</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-5 px-6 font-black text-slate-900 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center"><i class="fas fa-umbrella-beach"></i></div>
                                Miền Trung (Đà Nẵng, Nha Trang)
                            </td>
                            <td class="py-5 px-6 text-primary">Tháng 2 - Tháng 8</td>
                            <td class="py-5 px-6">25°C - 32°C (Nắng vàng / Biển êm)</td>
                            <td class="py-5 px-6 text-slate-500 font-medium">Tắm biển, lặn ngắm san hô, lễ hội pháo hoa quốc tế.</td>
                            <td class="py-5 px-6 text-right"><span class="px-3 py-1 bg-blue-50 text-blue-600 border border-blue-200 rounded-xl text-[9px] uppercase tracking-widest font-black">Rất thuận lợi</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-5 px-6 font-black text-slate-900 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-primary flex items-center justify-center"><i class="fas fa-water"></i></div>
                                Miền Tây & Phú Quốc
                            </td>
                            <td class="py-5 px-6 text-primary">Tháng 10 - Tháng 5</td>
                            <td class="py-5 px-6">27°C - 33°C (Khí hậu ôn hòa)</td>
                            <td class="py-5 px-6 text-slate-500 font-medium">Khám phá chợ nổi, miệt vườn trái cây, nghỉ dưỡng đảo ngọc.</td>
                            <td class="py-5 px-6 text-right"><span class="px-3 py-1 bg-emerald-50 text-primary border border-emerald-200 rounded-xl text-[9px] uppercase tracking-widest font-black">Mùa đẹp nhất</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Widget: Gợi ý Tuyến điểm Liên tuyến -->
        <div class="bg-gradient-to-br from-slate-900 to-dark p-12 rounded-[3rem] relative overflow-hidden group shadow-2xl border border-slate-800 flex flex-col lg:flex-row items-center justify-between gap-12">
            <div class="absolute inset-0 bg-primary/5 opacity-40 group-hover:opacity-60 transition-opacity"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/20 rounded-full blur-[100px]"></div>
            
            <div class="relative z-10 max-w-xl space-y-6">
                <span class="px-4 py-2 bg-primary/20 border border-primary/30 text-primary rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm inline-block">
                    Hành trình đặc biệt
                </span>
                <h3 class="text-4xl md:text-5xl font-black text-white tracking-tighter uppercase leading-tight m-0">
                    Khám phá lộ trình <span class="text-primary italic">Liên Tuyến</span>
                </h3>
                <p class="text-slate-400 text-sm font-medium leading-relaxed m-0">
                    Trải nghiệm xuyên suốt nhiều tỉnh thành trong một hành trình duy nhất. Tiết kiệm thời gian, chi phí và tận hưởng trọn vẹn dải đất hình chữ S.
                </p>
                <div class="flex flex-wrap gap-4 pt-2">
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4 backdrop-blur-md flex items-center gap-3 text-white text-xs font-bold">
                        <i class="fas fa-route text-primary text-lg"></i>
                        <div>
                            <p class="text-[9px] text-slate-400 uppercase tracking-widest m-0">Tuyến Di Sản</p>
                            <p class="m-0">Huế - Đà Nẵng - Hội An</p>
                        </div>
                    </div>
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4 backdrop-blur-md flex items-center gap-3 text-white text-xs font-bold">
                        <i class="fas fa-mountain text-primary text-lg"></i>
                        <div>
                            <p class="text-[9px] text-slate-400 uppercase tracking-widest m-0">Tuyến Đông Bắc</p>
                            <p class="m-0">Hà Giang - Cao Bằng - Bắc Kạn</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row gap-4 w-full lg:w-auto flex-shrink-0">
                <a href="{{ route('public.tours.index') }}" class="btn btn-primary !py-5 !px-10 rounded-2xl text-xs uppercase tracking-widest font-black shadow-xl shadow-primary/20 text-center">
                    Xem tour liên tuyến <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="tel:0123456789" class="btn btn-outline border-white/20 text-white hover:bg-white/10 !py-5 !px-10 rounded-2xl text-xs uppercase tracking-widest font-black flex items-center justify-center gap-2">
                    <i class="fas fa-phone-alt text-primary"></i> Tư vấn lộ trình
                </a>
            </div>
        </div>

    </div>
</section>
@endsection
