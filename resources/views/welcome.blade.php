@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<!-- Hero Section -->
<section class="relative h-[85vh] flex items-center justify-center overflow-hidden" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
    <!-- Hero Background -->
    <div class="absolute inset-0 bg-dark">
        <div class="absolute inset-0 bg-gradient-primary opacity-20"></div>
        @if(isset($settings['hero_image']))
            <img src="{{ asset('storage/' . $settings['hero_image']) }}" class="w-full h-full object-cover transition-transform duration-10000" :class="loaded ? 'scale-110' : 'scale-100'">
        @else
            <img src="https://images.unsplash.com/photo-1528127269322-539801943592?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" class="w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-dark via-dark/40 to-transparent"></div>
    </div>

    <div class="container relative z-10 text-center">
        <p class="text-primary text-[10px] font-black uppercase tracking-[0.6em] mb-6 drop-shadow-md transition-all duration-1000 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
            #{{ $settings['site_name'] ?? 'ANTIGRAVITY' }} TRAVEL AGENCY
        </p>
        <h1 class="text-6xl md:text-8xl font-black text-white tracking-tighter leading-none mb-8 transition-all duration-1000 delay-300 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
            Khám phá <span class="text-primary">Việt Nam</span><br>
            <span class="italic text-slate-300">theo cách của bạn</span>
        </h1>
        <p class="text-slate-300 text-lg md:text-xl font-medium mb-12 max-w-2xl mx-auto opacity-80 leading-relaxed transition-all duration-1000 delay-500 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
            Hơn 1000+ tour du lịch trọn gói, chuyên nghiệp và đẳng cấp đang chờ đón bạn khám phá.
        </p>
        
        <!-- Premium Search Box -->
        <div class="max-w-4xl mx-auto bg-white/10 backdrop-blur-2xl p-4 rounded-[2.5rem] border border-white/20 shadow-2xl">
            <form action="{{ route('public.tours.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative group">
                    <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors"></i>
                    <input type="text" name="search" placeholder="Bạn muốn đi đâu?" class="w-full bg-white border-none rounded-3xl py-4 pl-14 pr-6 text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-primary transition-all">
                </div>
                <div class="md:w-56 relative group">
                    <i class="fas fa-map-marker-alt absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors"></i>
                    <select name="destination" class="w-full bg-white border-none rounded-3xl py-4 pl-14 pr-10 text-sm font-bold text-slate-600 appearance-none focus:ring-2 focus:ring-primary transition-all">
                        <option value="">Tất cả điểm đến</option>
                        @foreach($destinations as $dest)
                            <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary px-10 rounded-[1.8rem]">Tìm kiếm</button>
            </form>
        </div>
    </div>
</section>

<!-- Stats Bar & Trust Signals -->
<section class="container mt-8 md:mt-10 relative z-20" x-data="{ 
    stats: [
        { label: 'Khách hàng', target: 150, current: 0, suffix: 'K+' },
        { label: 'Điểm đến', target: 100, current: 0, suffix: '+' },
        { label: 'An toàn', target: 99, current: 0, suffix: '%' },
        { label: 'Đánh giá', target: 4.9, current: 0, suffix: '/5' }
    ],
    startCount(stat) {
        let start = 0;
        let end = stat.target;
        let duration = 2000;
        let step = (end / (duration / 16));
        let interval = setInterval(() => {
            stat.current += step;
            if (stat.current >= end) {
                stat.current = end;
                clearInterval(interval);
            }
        }, 16);
    }
}" x-intersect.once="stats.forEach(s => startCount(s))">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <template x-for="stat in stats" :key="stat.label">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl flex items-center gap-6 group hover:bg-primary transition-all duration-700 hover:-translate-y-2">
                <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center text-primary group-hover:bg-white transition-all shadow-sm">
                    <i class="fas text-xl" :class="{
                        'fa-users': stat.label === 'Khách hàng',
                        'fa-map': stat.label === 'Điểm đến',
                        'fa-shield-alt': stat.label === 'An toàn',
                        'fa-star': stat.label === 'Đánh giá'
                    }"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900 group-hover:text-white transition-all tracking-tighter" 
                       x-text="stat.label === 'Đánh giá' ? stat.current.toFixed(1) + stat.suffix : Math.floor(stat.current) + stat.suffix"></p>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest group-hover:text-teal-100 transition-all" x-text="stat.label"></p>
                </div>
            </div>
        </template>
    </div>
</section>

<!-- Trending Ticker -->
<section class="py-12 bg-white overflow-hidden border-b border-slate-50">
    <div class="flex items-center">
        <div class="whitespace-nowrap flex animate-marquee group">
            @foreach(array_merge($destinations->toArray(), $destinations->toArray()) as $dest)
                <div class="inline-flex items-center gap-4 px-12 group/item">
                    <span class="w-2 h-2 rounded-full bg-primary/30 group-hover/item:bg-primary transition-colors"></span>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em] group-hover/item:text-slate-900 transition-colors">{{ $dest['name'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-24 bg-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-64 h-64 bg-primary/5 rounded-full blur-[100px] -ml-32 -mt-32"></div>
    <div class="container">
        <div class="max-w-3xl mb-16 px-4">
            <div class="flex items-center gap-3">
                <span class="w-8 h-[2px] bg-primary"></span>
                <p class="text-primary text-[10px] font-black uppercase tracking-[0.4em]">Giá trị cốt lõi</p>
            </div>
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter uppercase mt-4">Tại sao nên chọn <span class="text-primary">{{ $settings['site_name'] ?? 'Tour Travel' }}</span>?</h2>
            <p class="text-slate-500 mt-6 font-medium leading-relaxed">Chúng tôi mang đến những trải nghiệm du lịch khác biệt, kết hợp hoàn hảo giữa sự chuyên nghiệp, an toàn và những cảm xúc thăng hoa trên mỗi cung đường.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100 hover:bg-white hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 group">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-primary mb-8 shadow-sm group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fas fa-shield-check text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter mb-4">An toàn tuyệt đối</h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">Mọi hành trình đều được bảo hiểm và giám sát chặt chẽ bởi đội ngũ chuyên gia giàu kinh nghiệm.</p>
            </div>
            <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100 hover:bg-white hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 group">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-primary mb-8 shadow-sm group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fas fa-tags text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter mb-4">Giá tốt nhất</h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">Cam kết mức giá cạnh tranh nhất thị trường cùng nhiều ưu đãi hấp dẫn dành cho khách hàng thân thiết.</p>
            </div>
            <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100 hover:bg-white hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 group">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-primary mb-8 shadow-sm group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fas fa-user-tie text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter mb-4">Dịch vụ tận tâm</h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">Đội ngũ tư vấn và hướng dẫn viên luôn sẵn sàng hỗ trợ bạn 24/7 với tinh thần trách nhiệm cao nhất.</p>
            </div>
            <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100 hover:bg-white hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 group">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-primary mb-8 shadow-sm group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fas fa-map-marked-alt text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter mb-4">Lịch trình đa dạng</h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">Hàng ngàn tour độc đáo, từ nghỉ dưỡng sang trọng đến khám phá mạo hiểm, đáp ứng mọi nhu cầu.</p>
            </div>
        </div>
    </div>
</section>


<!-- Popular Tours -->
<section id="tours" class="py-24 bg-slate-50 overflow-hidden">
    <div class="container">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 px-4">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-primary"></span>
                    <p class="text-primary text-[10px] font-black uppercase tracking-[0.4em]">Danh sác tour hot</p>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter uppercase">Hành trình <span class="text-primary">Mới nhất</span></h2>
            </div>
            <a href="{{ route('public.tours.index') }}" class="group text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition-all mt-6 md:mt-0">
                Khám phá tất cả <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @foreach($tours as $tour)
            <a href="{{ route('public.tours.show', $tour->slug) }}" class="group h-full flex flex-col cursor-pointer block">
                <div class="relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-2xl shadow-slate-200 mb-8">
                    @php $primaryImage = $tour->images->where('is_primary', 1)->first() ?? $tour->images->first(); @endphp
                    @if($primaryImage)
                        <img src="{{ $primaryImage->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                    @else
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                            <i class="fas fa-image text-5xl"></i>
                        </div>
                    @endif
                    
                    <!-- Overlay Info -->
                    <div class="absolute inset-0 bg-gradient-to-t from-dark/90 via-dark/20 to-transparent p-8 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <span class="px-3 py-1.5 bg-white/20 backdrop-blur-md rounded-xl text-[9px] font-black text-white uppercase tracking-widest border border-white/20">
                                <i class="far fa-clock mr-1"></i> {{ $tour->duration }}
                            </span>
                            <button @click.prevent.stop="alert('Đã lưu tour vào danh sách yêu thích!')" class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white hover:bg-rose-500 transition-all border border-white/20">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        <div>
                            <p class="text-primary text-[10px] font-black uppercase tracking-widest mb-2">{{ $tour->destinations->pluck('name')->implode(', ') }}</p>
                            <h3 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight leading-none mb-4 group-hover:text-primary transition-colors">{{ $tour->title }}</h3>
                            <div class="flex items-center justify-between">
                                <p class="text-white text-lg font-black">{{ number_format($tour->base_price) }}đ</p>
                                <span class="p-3 bg-white rounded-xl text-dark group-hover:bg-primary group-hover:text-white transition-all">
                                    <i class="fas fa-arrow-right text-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Destinations -->
<section class="py-24 bg-white relative">
    <div class="container text-center mb-16">
        <p class="text-primary text-[10px] font-black uppercase tracking-[0.4em] mb-4">Điểm đến nổi bật</p>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter uppercase whitespace-pre-line">Khám phá <span class="italic text-primary font-bold">Vẻ đẹp</span> tiềm ẩn</h2>
    </div>

    <div class="container relative grid grid-cols-2 md:grid-cols-4 gap-4 px-4">
        @foreach($destinations as $index => $dest)
        <a href="{{ route('public.destinations.show', $dest->id) }}" class="relative group rounded-[2rem] overflow-hidden aspect-[1/1.2] {{ $index % 3 == 0 ? 'md:aspect-[1/1.5]' : '' }} cursor-pointer bg-slate-100 block">
            @if($dest->image_path)
                <img src="{{ $dest->image_url }}" class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-1000">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
            <div class="absolute bottom-6 left-6 text-left">
                <p class="text-[9px] text-primary font-black uppercase tracking-widest mb-1">{{ $dest->location }}</p>
                <h4 class="text-white text-sm font-black uppercase tracking-tight">{{ $dest->name }}</h4>
            </div>
        </a>
        @endforeach
    </div>
</section>

<!-- Testimonials -->
<section class="py-24 bg-dark relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-primary opacity-10"></div>
    <div class="container relative z-10">
        <div class="text-center mb-16">
            <p class="text-primary text-[10px] font-black uppercase tracking-[0.4em] mb-4">Khách hàng nói gì</p>
            <h2 class="text-4xl md:text-5xl font-black text-white tracking-tighter uppercase">Cảm nhận <span class="text-primary italic">Hành trình</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white/5 backdrop-blur-md border border-white/10 p-10 rounded-[3rem] relative">
                <i class="fas fa-quote-left text-primary text-4xl opacity-20 absolute top-8 left-8"></i>
                <div class="relative z-10">
                    <p class="text-slate-300 font-medium italic leading-relaxed mb-8">"Một chuyến đi tuyệt vời vượt xa mong đợi! Sự chu đáo từ việc đưa đón đến lựa chọn nhà hàng thực sự làm tôi ấn tượng. Chắc chắn sẽ quay lại."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name=Nguyen+An&background=fbbf24&color=fff" class="w-12 h-12 rounded-2xl object-cover">
                        <div>
                            <h4 class="text-sm font-black text-white uppercase tracking-tighter">Nguyễn Văn An</h4>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Business Owner</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white/5 backdrop-blur-md border border-white/10 p-10 rounded-[3rem] relative">
                <i class="fas fa-quote-left text-primary text-4xl opacity-20 absolute top-8 left-8"></i>
                <div class="relative z-10">
                    <p class="text-slate-300 font-medium italic leading-relaxed mb-8">"Lịch trình rất khoa học, không quá dày đặc giúp gia đình tôi có thời gian thư giãn thực sự. Hướng dẫn viên rất am hiểu văn hóa địa phương."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name=Hoang+Lan&background=10b981&color=fff" class="w-12 h-12 rounded-2xl object-cover">
                        <div>
                            <h4 class="text-sm font-black text-white uppercase tracking-tighter">Trần Hoàng Lan</h4>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Photographer</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white/5 backdrop-blur-md border border-white/10 p-10 rounded-[3rem] relative">
                <i class="fas fa-quote-left text-primary text-4xl opacity-20 absolute top-8 left-8"></i>
                <div class="relative z-10">
                    <p class="text-slate-300 font-medium italic leading-relaxed mb-8">"Hệ thống đặt tour rất nhanh chóng và tiện lợi. Tôi đặc biệt thích cách các bạn xử lý những yêu cầu phát sinh một cách chuyên nghiệp và linh hoạt."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name=Phan+Minh&background=3b82f6&color=fff" class="w-12 h-12 rounded-2xl object-cover">
                        <div>
                            <h4 class="text-sm font-black text-white uppercase tracking-tighter">Phạm Minh Quân</h4>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Travel Blogger</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Special Offer Section -->
@if($special_offer)
<section class="py-24 bg-slate-50 overflow-hidden">
    <div class="container">
        <div class="bg-dark rounded-[3rem] p-8 md:p-16 relative overflow-hidden flex flex-col md:flex-row items-center gap-12">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-[100px] -mr-32 -mt-32"></div>
            <div class="relative z-10 md:w-1/2">
                <span class="inline-block px-4 py-2 bg-primary/10 rounded-xl text-[10px] font-black text-primary uppercase tracking-widest border border-primary/20 mb-6">Ưu đãi độc quyền</span>
                <h2 class="text-4xl md:text-6xl font-black text-white tracking-tighter uppercase mb-6 leading-none">
                    {{ $special_offer->title }}
                </h2>
                <p class="text-slate-400 text-lg mb-10 leading-relaxed font-medium">
                    {{ $special_offer->description }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="bg-white/5 border border-white/10 px-6 py-4 rounded-2xl flex items-center gap-4">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">Mã ưu đãi:</span>
                        <span class="text-xl font-black text-primary font-mono tracking-widest">{{ $special_offer->code }}</span>
                    </div>
                    <a href="{{ route('public.tours.index') }}" class="btn btn-secondary px-10">Khám phá ngay</a>
                </div>
            </div>
            <div class="md:w-1/2 relative group">
                <div class="relative bg-white/5 rounded-[2.5rem] p-4 backdrop-blur-sm border border-white/10">
                    <img src="{{ asset('storage/' . $special_offer->image_path) }}" class="rounded-[2rem] shadow-2xl w-full aspect-video object-cover">
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-primary rounded-[2rem] flex flex-col items-center justify-center rotate-12 shadow-2xl shadow-primary/40 group-hover:rotate-0 transition-transform">
                        <span class="text-[10px] font-black text-dark text-center uppercase tracking-widest leading-none">Giảm<br>Đến</span>
                        <span class="text-2xl font-black text-white">
                            {{ $special_offer->discount_type == 'Percentage' ? $special_offer->discount_value . '%' : number_format($special_offer->discount_value) . 'đ' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Latest News -->
<section class="py-24 bg-white">
    <div class="container mb-16">
        <div class="flex justify-between items-end">
            <div>
                <p class="text-primary text-[10px] font-black uppercase tracking-[0.4em] mb-4">Cẩm nang du lịch</p>
                <h2 class="text-4xl md:text-5xl font-black text-dark tracking-tighter uppercase whitespace-pre-line">Tin tức <span class="text-primary">Mới nhất</span></h2>
            </div>
            <a href="{{ route('public.news.index') }}" class="text-[10px] font-black tracking-widest uppercase text-slate-400 hover:text-primary transition-all">Xem tất cả <i class="fas fa-chevron-right ml-2"></i></a>
        </div>
    </div>
    
    <div class="container grid grid-cols-1 md:grid-cols-3 gap-12 px-4">
        @foreach($news as $item)
        <a href="{{ route('public.news.show', $item->slug) }}" class="group h-full flex flex-col cursor-pointer block">
            <div class="relative aspect-[16/10] rounded-[2rem] overflow-hidden mb-6 bg-slate-100 shadow-sm border border-slate-50">
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                @endif
                <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-lg text-[8px] font-black text-primary uppercase tracking-widest shadow-sm">
                    {{ $item->category->name }}
                </div>
            </div>
            <div class="flex-1 flex flex-col">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <i class="far fa-calendar-alt text-primary"></i> {{ $item->created_at->format('d/m/Y') }} — bởi {{ $item->author->full_name }}
                </span>
                <h3 class="text-xl font-black text-dark uppercase tracking-tighter group-hover:text-primary transition-colors leading-tight line-clamp-2">
                    {{ $item->title }}
                </h3>
            </div>
        </a>
        @endforeach
    </div>
</section>

<!-- Newsletter -->
<section class="py-24 bg-slate-50">
    <div class="container px-4">
        <div class="bg-primary rounded-[4rem] p-12 md:p-20 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-[100px] -mr-48 -mt-48"></div>
            <div class="relative z-10 max-w-xl">
                <h2 class="text-4xl md:text-5xl font-black text-white tracking-tighter uppercase mb-6 leading-none">Cùng viết nên hành trình mới</h2>
                <p class="text-white/80 text-lg font-medium">Đăng ký nhận bản tin để không bỏ lỡ những ưu đãi độc quyền và cảm hứng du lịch mỗi tuần.</p>
            </div>
            <div class="relative z-10 w-full md:w-auto">
                <form @submit.prevent="alert('Cảm ơn bạn! Đăng ký bản tin thành công.')" class="flex flex-col sm:flex-row gap-4">
                    <input type="email" required placeholder="Email của bạn..." class="bg-white/10 border border-white/20 rounded-[2rem] py-5 px-8 text-white placeholder-white/50 focus:ring-2 focus:ring-white/30 backdrop-blur-sm min-w-[300px]">
                    <button type="submit" class="btn bg-white text-primary hover:bg-slate-100 px-10 py-5 rounded-[2rem] font-black uppercase tracking-widest text-xs">Đăng ký ngay</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
