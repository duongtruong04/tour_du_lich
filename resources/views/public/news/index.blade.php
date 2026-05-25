@extends('layouts.app')

@section('title', 'Cẩm nang & Kinh nghiệm du lịch')

@section('content')
<!-- Breadcrumb Header -->
<header class="breadcrumb-header relative bg-dark py-24 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1499591934245-40b55745b905?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" class="absolute inset-0 w-full h-full object-cover opacity-30">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
    <div class="container relative z-10 text-center space-y-6">
        <nav class="flex justify-center items-center gap-3 text-xs font-bold uppercase tracking-widest text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Trang chủ</a>
            <i class="fas fa-chevron-right text-[8px] opacity-40"></i>
            <span class="text-white">Cẩm nang du lịch</span>
        </nav>
        <h1 class="text-6xl md:text-9xl font-black text-white tracking-tighter uppercase leading-none opacity-10 absolute bottom-0 left-0 right-0 select-none pointer-events-none">MAGAZINE</h1>
        <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter uppercase m-0">Kinh nghiệm & <span class="text-primary italic">Cảm hứng</span></h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-sm font-medium leading-relaxed m-0">
            Tổng hợp những bài viết đánh giá chân thực, bí quyết xê dịch thông minh và xu hướng du lịch mới nhất từ các chuyên gia hàng đầu.
        </p>
    </div>
</header>

<!-- Main Magazine Section -->
<section class="py-16 bg-slate-50" x-data="{ 
    activeCategory: 'all', 
    searchQuery: '',
    filteredCount: {{ $news->count() }},
    updateCount() {
        setTimeout(() => {
            let visible = document.querySelectorAll('.news-card:not([style*=\'display: none\'])');
            this.filteredCount = visible.length;
        }, 50);
    }
}">
    <div class="container">
        
        <!-- Interactive Toolbar: Categories & Search -->
        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6 mb-12 relative">
            <!-- Category Tabs -->
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
                <button @click="activeCategory = 'all'; updateCount()" :class="activeCategory === 'all' ? 'bg-primary text-white shadow-lg shadow-primary/20 font-bold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'" class="px-6 py-3 rounded-2xl text-xs uppercase tracking-widest transition-all whitespace-nowrap">
                    Tất cả ({{ $news->count() }})
                </button>
                @foreach($categories as $cat)
                    <button @click="activeCategory = '{{ $cat->name }}'; updateCount()" :class="activeCategory === '{{ $cat->name }}' ? 'bg-primary text-white shadow-lg shadow-primary/20 font-bold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'" class="px-6 py-3 rounded-2xl text-xs uppercase tracking-widest transition-all whitespace-nowrap">
                        {{ $cat->name }} ({{ $cat->news_count }})
                    </button>
                @endforeach
            </div>

            <!-- Live Search Input -->
            <div class="relative w-full md:w-80 group">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors"></i>
                <input type="text" x-model="searchQuery" @input="updateCount()" placeholder="Tìm nhanh bài viết..." class="w-full bg-slate-50 border-none rounded-2xl py-3.5 pl-12 pr-10 text-xs font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-primary transition-all">
                <button x-show="searchQuery" @click="searchQuery = ''; updateCount()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 text-xs">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-10">
            
            <!-- Left Side: News Articles Grid -->
            <div class="flex-1 space-y-12">
                
                <!-- Featured Article (Only visible when no filter applied) -->
                @if(!request('category') && !request('search') && $news->currentPage() == 1 && $news->count() > 0)
                    @php $featured = $news->first(); @endphp
                    <div x-show="activeCategory === 'all' && searchQuery === ''" x-transition:enter="transition ease-out duration-300" class="group">
                        <a href="{{ route('public.news.show', $featured->slug) }}" class="grid grid-cols-1 md:grid-cols-2 bg-white rounded-[3.5rem] overflow-hidden shadow-xl border border-slate-100 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-700">
                            <div class="relative aspect-video md:aspect-auto overflow-hidden bg-slate-100">
                                <img src="{{ $featured->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                                <div class="absolute top-8 left-8 px-5 py-2 bg-primary text-white rounded-2xl text-[9px] font-black uppercase tracking-widest shadow-xl">
                                    🔥 Bài viết nổi bật
                                </div>
                            </div>
                            <div class="p-10 md:p-14 flex flex-col justify-center space-y-6">
                                <span class="text-[10px] font-black text-primary uppercase tracking-[0.3em] flex items-center gap-3 m-0">
                                    <span class="w-6 h-[2px] bg-primary"></span> {{ $featured->category->name ?? 'Cẩm nang' }}
                                </span>
                                <h2 class="text-2xl md:text-4xl font-black text-slate-900 uppercase tracking-tighter leading-tight m-0 group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $featured->title }}
                                </h2>
                                <p class="text-slate-500 text-sm font-medium leading-relaxed m-0 line-clamp-3 italic">
                                    {{ Str::limit(strip_tags($featured->content), 180) }}
                                </p>
                                <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($featured->author->full_name ?? 'Admin') }}" class="w-10 h-10 rounded-xl border border-slate-100 shadow-sm">
                                        <div>
                                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-widest m-0">{{ $featured->author->full_name ?? 'Ban biên tập' }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase m-0">{{ $featured->created_at->format('d M, Y') }} • 5 phút đọc</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-black text-primary uppercase tracking-widest flex items-center gap-2">Đọc ngay <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                <!-- Results Count Indicator -->
                <div class="flex items-center justify-between px-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest m-0">
                        Hiển thị <span x-text="filteredCount" class="text-slate-900 font-black"></span> bài viết cẩm nang phù hợp
                    </p>
                </div>

                <!-- Articles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($news as $item)
                    <a x-show="(activeCategory === 'all' || activeCategory === '{{ $item->category->name ?? '' }}') && ('{{ Str::lower($item->title) }}'.includes(searchQuery.toLowerCase()) || '{{ Str::lower(strip_tags($item->content)) }}'.includes(searchQuery.toLowerCase()))" 
                       x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" 
                       href="{{ route('public.news.show', $item->slug) }}" class="news-card group flex flex-col bg-white rounded-[3rem] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border border-slate-100 h-full">
                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                            @if($item->image_path)
                                <img src="{{ $item->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <i class="fas fa-newspaper text-5xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-6 left-6 px-4 py-2 bg-white/90 backdrop-blur-md rounded-2xl text-[9px] font-black text-primary uppercase tracking-widest shadow-sm">
                                {{ $item->category->name ?? 'Cẩm nang' }}
                            </div>
                        </div>
                        
                        <div class="p-8 flex-1 flex flex-col justify-between space-y-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between text-[9px] font-bold text-slate-400 uppercase tracking-widest m-0">
                                    <span class="flex items-center gap-2">
                                        <i class="far fa-calendar-alt text-primary"></i> {{ $item->created_at->format('d/m/Y') }}
                                    </span>
                                    <span class="flex items-center gap-1 text-slate-500">
                                        <i class="far fa-clock text-amber-500"></i> ~3 phút đọc
                                    </span>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter leading-tight m-0 group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $item->title }}
                                </h3>
                                <p class="text-xs text-slate-500 font-medium line-clamp-2 m-0 leading-relaxed">
                                    {{ Str::limit(strip_tags($item->content), 120) }}
                                </p>
                            </div>

                            <div class="pt-6 border-t border-slate-50 flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-primary transition-colors">
                                <span class="text-slate-600 flex items-center gap-2"><i class="far fa-user text-primary"></i> {{ $item->author->full_name ?? 'Ban biên tập' }}</span>
                                <span class="flex items-center gap-1">Khám phá <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i></span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Empty State -->
                <div x-show="filteredCount === 0" class="text-center py-24 bg-white rounded-[3rem] shadow-sm border border-dashed border-slate-200 space-y-6">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-300">
                        <i class="fas fa-file-alt text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tighter uppercase m-0">Không tìm thấy bài viết phù hợp</h3>
                    <p class="text-slate-500 font-medium max-w-md mx-auto m-0 leading-relaxed">
                        Không có cẩm nang nào khớp với tiêu chí tìm kiếm của bạn. Vui lòng thử tìm kiếm với từ khóa khác.
                    </p>
                    <button @click="searchQuery = ''; activeCategory = 'all'; updateCount()" class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-redo"></i> Đặt lại bộ lọc
                    </button>
                </div>

                <!-- Pagination -->
                <div class="mt-16 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex justify-center">
                    {{ $news->appends(request()->query())->links() }}
                </div>

            </div>

            <!-- Right Side: Powerful Functional Sidebar -->
            <aside class="w-full lg:w-80 flex-shrink-0 space-y-8">
                
                <!-- WIDGET 1: Bảng Tính Chi Phí Du Lịch Tự Động (Interactive Budget Calculator) -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100 space-y-6" x-data="{
                    region: 'north',
                    days: 3,
                    tier: 'standard',
                    calc() {
                        let base = this.region === 'north' ? 1200000 : (this.region === 'central' ? 1500000 : 1800000);
                        let mult = this.tier === 'budget' ? 0.8 : (this.tier === 'standard' ? 1.0 : 1.6);
                        return (base * this.days * mult).toLocaleString() + ' đ';
                    }
                }">
                    <h5 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 border-l-4 border-primary pl-3 m-0">
                        <i class="fas fa-calculator text-primary"></i> Dự toán chi phí du lịch
                    </h5>
                    <p class="text-xs text-slate-500 font-medium m-0 leading-relaxed">Công cụ hỗ trợ tính toán nhanh ngân sách dự kiến cho chuyến đi sắp tới của bạn.</p>

                    <div class="space-y-4 pt-2 text-xs font-bold text-slate-600">
                        <!-- Region -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Điểm đến dự kiến</label>
                            <select x-model="region" class="w-full bg-slate-50 border-none rounded-2xl py-3 px-4 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-primary cursor-pointer">
                                <option value="north">Miền Bắc (Sapa, Hạ Long...)</option>
                                <option value="central">Miền Trung (Đà Nẵng, Huế...)</option>
                                <option value="south">Miền Nam (Phú Quốc, Cần Thơ...)</option>
                            </select>
                        </div>

                        <!-- Days -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Số ngày khởi hành: <span x-text="days + ' ngày'" class="text-primary font-black"></span></label>
                            <input type="range" x-model.number="days" min="1" max="10" class="w-full accent-primary cursor-pointer">
                        </div>

                        <!-- Tier -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Tiêu chuẩn dịch vụ</label>
                            <div class="grid grid-cols-3 gap-2 text-[10px]">
                                <button type="button" @click="tier = 'budget'" :class="tier === 'budget' ? 'bg-primary text-white font-black shadow-md' : 'bg-slate-50 text-slate-600'" class="py-2 rounded-xl transition-all">Tiết kiệm</button>
                                <button type="button" @click="tier = 'standard'" :class="tier === 'standard' ? 'bg-primary text-white font-black shadow-md' : 'bg-slate-50 text-slate-600'" class="py-2 rounded-xl transition-all">Tiêu chuẩn</button>
                                <button type="button" @click="tier = 'luxury'" :class="tier === 'luxury' ? 'bg-primary text-white font-black shadow-md' : 'bg-slate-50 text-slate-600'" class="py-2 rounded-xl transition-all">Cao cấp</button>
                            </div>
                        </div>

                        <!-- Result Box -->
                        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-center space-y-1 my-4">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest m-0">Chi phí ước tính / Khách</p>
                            <p class="text-xl font-black text-primary m-0" x-text="calc()"></p>
                        </div>

                        <a href="{{ route('public.tours.index') }}" class="btn btn-primary w-full !py-3 rounded-xl text-center text-[10px] block uppercase tracking-widest shadow-lg shadow-primary/20">
                            Tìm tour theo ngân sách <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- WIDGET 2: Đăng ký nhận cẩm nang E-Book -->
                <div class="bg-gradient-to-br from-primary to-teal-600 p-8 rounded-[2.5rem] relative overflow-hidden group shadow-xl text-dark space-y-6">
                    <div class="absolute inset-0 bg-white/10 group-hover:bg-white/20 transition-all"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full blur-2xl -mr-16 -mt-16"></div>
                    <div class="relative z-10 space-y-4">
                        <i class="fas fa-book-open text-3xl text-dark mb-2 block"></i>
                        <h5 class="text-2xl font-black tracking-tighter uppercase leading-tight m-0 italic">Nhận Miễn Phí<br>E-Book 2026</h5>
                        <p class="text-xs font-bold text-dark/80 m-0 leading-relaxed">Cẩm nang 50 điểm check-in bí mật và voucher giảm 500k cho lần đặt tour đầu tiên.</p>
                        <form @submit.prevent="alert('Cảm ơn bạn! E-Book đã được gửi vào email.')" class="space-y-3 pt-2">
                            <input type="email" required placeholder="Nhập email của bạn..." class="w-full bg-white/30 border-white/40 rounded-xl px-4 py-3 text-xs font-bold text-dark placeholder-dark/50 focus:ring-2 focus:ring-dark transition-all">
                            <button type="submit" class="w-full py-3 bg-dark text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:shadow-xl transition-all shadow-md">
                                Nhận E-Book Ngay
                            </button>
                        </form>
                    </div>
                </div>

                <!-- WIDGET 3: Bài viết xem nhiều nhất (Trending) -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100 space-y-6">
                    <h5 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 border-l-4 border-primary pl-3 m-0">
                        <i class="fas fa-fire text-rose-500"></i> Bài viết nổi bật
                    </h5>
                    <div class="space-y-6 pt-2">
                        @foreach($news->take(4) as $index => $trn)
                            <a href="{{ route('public.news.show', $trn->slug) }}" class="flex items-center gap-4 group">
                                <span class="text-2xl font-black text-slate-200 group-hover:text-primary transition-colors font-mono w-6 text-center">
                                    0{{ $index + 1 }}
                                </span>
                                <div class="flex-1 space-y-1 overflow-hidden">
                                    <h6 class="text-xs font-black text-slate-800 uppercase tracking-tight leading-tight line-clamp-2 group-hover:text-primary transition-colors m-0">
                                        {{ $trn->title }}
                                    </h6>
                                    <p class="text-[9px] font-bold text-slate-400 m-0">{{ $trn->created_at->format('d/m/Y') }} • {{ number_format($trn->view_count ?? 120) }} lượt xem</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- WIDGET 4: Tour Gợi Ý Ngẫu Nhiên -->
                @if($recommended_tour)
                <div class="bg-gradient-to-br from-slate-900 to-dark p-8 rounded-[2.5rem] relative overflow-hidden group shadow-xl border border-slate-800 space-y-4">
                    <div class="absolute inset-0 bg-primary/10 opacity-30"></div>
                    <div class="relative z-10 space-y-3">
                        <span class="px-3 py-1 bg-primary/20 text-primary border border-primary/30 rounded-xl text-[9px] font-black uppercase tracking-widest inline-block">
                            Góc xê dịch
                        </span>
                        <h5 class="text-white text-base font-black tracking-tight leading-tight uppercase m-0">{{ $recommended_tour->title }}</h5>
                        <p class="text-xs text-slate-400 font-medium line-clamp-2 m-0">{{ $recommended_tour->summary }}</p>
                        <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                            <p class="text-lg font-black text-primary m-0">{{ number_format($recommended_tour->base_price) }}đ</p>
                            <a href="{{ route('public.tours.show', $recommended_tour->slug) }}" class="btn btn-primary !py-2 !px-4 rounded-xl !text-[10px]">Khám phá</a>
                        </div>
                    </div>
                </div>
                @endif

            </aside>

        </div>
    </div>
</section>
@endsection
