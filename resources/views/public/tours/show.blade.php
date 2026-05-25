@extends('layouts.app')

@section('title', $tour->title)

@section('content')
<!-- Hero / Header -->
<section class="relative h-[60vh] md:h-[75vh] flex items-end">
    <div class="absolute inset-0 bg-dark overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-dark/90 via-dark/20 to-transparent z-10"></div>
        @php $primaryImage = $tour->images->where('is_primary', 1)->first() ?? $tour->images->first(); @endphp
        @if($primaryImage)
            <img src="{{ $primaryImage->image_url }}" class="w-full h-full object-cover">
        @endif
    </div>
    
    <div class="container relative z-20 pb-16">
        <nav class="breadcrumb-nav mb-8">
            <a href="{{ route('home') }}">Trang chủ</a>
            <i class="fas fa-chevron-right text-[8px] opacity-50"></i>
            <a href="{{ route('public.tours.index') }}">Tour du lịch</a>
            <i class="fas fa-chevron-right text-[8px] opacity-50"></i>
            <span class="text-white">{{ $tour->title }}</span>
        </nav>
        <div class="max-w-4xl">
            <p class="text-primary text-[10px] font-black uppercase tracking-[0.6em] mb-4">#{{ $tour->destinations->pluck('name')->implode(', ') }}</p>
            <h1 class="text-4xl md:text-7xl font-black text-white tracking-tighter leading-none mb-8 uppercase">{{ $tour->title }}</h1>
            
            <div class="flex flex-wrap gap-6 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-primary border border-white/10">
                        <i class="far fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Thời gian</p>
                        <p class="text-sm font-bold text-white uppercase">{{ $tour->duration }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-primary border border-white/10">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Khởi hành</p>
                        <p class="text-sm font-bold text-white uppercase">{{ $tour->destinations->first()->name ?? 'Toàn quốc' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-primary border border-white/10">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Phương tiện</p>
                        <p class="text-sm font-bold text-white uppercase">{{ $tour->transportation ?? 'Xe du lịch' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Value Props Bar -->
<section class="py-12 bg-slate-50 border-y border-slate-100">
    <div class="container">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="flex items-center gap-4 group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm group-hover:bg-primary group-hover:text-white transition-all duration-500">
                    <i class="fas fa-utensils text-xl"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ẩm thực</h4>
                    <p class="text-xs font-black text-slate-800 uppercase">Bao gồm bữa ăn</p>
                </div>
            </div>
            <div class="flex items-center gap-4 group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm group-hover:bg-primary group-hover:text-white transition-all duration-500">
                    <i class="fas fa-bus text-xl"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Di chuyển</h4>
                    <p class="text-xs font-black text-slate-800 uppercase">Xe đời mới 2024</p>
                </div>
            </div>
            <div class="flex items-center gap-4 group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm group-hover:bg-primary group-hover:text-white transition-all duration-500">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Bảo hiểm</h4>
                    <p class="text-xs font-black text-slate-800 uppercase">Mức 100tr/vụ</p>
                </div>
            </div>
            <div class="flex items-center gap-4 group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm group-hover:bg-primary group-hover:text-white transition-all duration-500">
                    <i class="fas fa-headset text-xl"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Hỗ trợ</h4>
                    <p class="text-xs font-black text-slate-800 uppercase">24/7 Miễn phí</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Grid -->
<section class="py-24 bg-white" x-data="{ activeTab: 'itinerary' }">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            
            <!-- Left Side: Main Info -->
            <div class="lg:col-span-2 space-y-16">
                
                <!-- Tab Headers -->
                <div class="flex items-center gap-4 md:gap-8 border-b border-slate-100 pb-1 overflow-x-auto no-scrollbar">
                    <button @click="activeTab = 'itinerary'" :class="activeTab === 'itinerary' ? 'bg-primary text-white shadow-lg shadow-emerald-500/20' : 'bg-white text-slate-500 hover:bg-slate-50'" class="px-6 md:px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        Lịch trình
                    </button>
                    <button @click="activeTab = 'policy'" :class="activeTab === 'policy' ? 'bg-primary text-white shadow-lg shadow-emerald-500/20' : 'bg-white text-slate-500 hover:bg-slate-50'" class="px-6 md:px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        Chính sách
                    </button>
                    <button @click="activeTab = 'faq'" :class="activeTab === 'faq' ? 'bg-primary text-white shadow-lg shadow-emerald-500/20' : 'bg-white text-slate-500 hover:bg-slate-50'" class="px-6 md:px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        Hỏi đáp
                    </button>
                    <button @click="activeTab = 'map'" :class="activeTab === 'map' ? 'bg-primary text-white shadow-lg shadow-emerald-500/20' : 'bg-white text-slate-500 hover:bg-slate-50'" class="px-6 md:px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        Bản đồ
                    </button>
                    <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'bg-primary text-white shadow-lg shadow-emerald-500/20' : 'bg-white text-slate-500 hover:bg-slate-50'" class="px-6 md:px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        Đánh giá ({{ $tour->reviews->count() }})
                    </button>
                </div>

                <!-- Tab: Itinerary -->
                <div x-show="activeTab === 'itinerary'" class="space-y-12 animate-fade-in">
                    <div class="prose prose-slate max-w-none">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase mb-6">Mô tả hành trình</h3>
                        <p class="text-slate-600 font-medium leading-relaxed italic border-l-4 border-primary pl-6 py-2 bg-slate-50 rounded-r-2xl mb-10">
                            {{ $tour->summary }}
                        </p>
                        <div class="text-slate-700 leading-relaxed font-medium text-lg prose-emerald">
                            {!! $tour->itinerary !!}
                        </div>
                    </div>

                    <!-- Highlight Timeline -->
                    <div class="space-y-8 mt-16">
                        @foreach($tour->destinations as $index => $dest)
                        <div class="flex gap-8 relative group">
                            <div class="flex-shrink-0 w-12 flex flex-col items-center">
                                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white font-black z-10 shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform">
                                    {{ $index + 1 }}
                                </div>
                                @if($index < count($tour->destinations) - 1)
                                <div class="w-1 h-full bg-slate-100 absolute top-12 left-1/2 -ml-[2px] z-0"></div>
                                @endif
                            </div>
                            <div class="pb-12 pt-1 flex-1">
                                <h4 class="text-xl font-black text-slate-900 uppercase tracking-tighter mb-2 group-hover:text-primary transition-colors">{{ $dest->name }}</h4>
                                <p class="text-sm font-medium text-slate-500 italic">{{ $dest->location }}</p>
                                <div class="mt-6 p-6 bg-slate-50 border border-slate-100 rounded-2xl text-slate-600 leading-relaxed font-medium">
                                    {{ $dest->description ?? 'Khám phá vẻ đẹp thiên nhiên và văn hóa đặc sắc tại ' . $dest->name . '. Trải nghiệm những hoạt động thú vị cùng đoàn.' }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- LƯU Ý HÀNH TRÌNH & HƯỚNG DẪN VIÊN -->
                    <div class="mt-12 p-8 bg-slate-50 rounded-3xl border border-slate-100 space-y-6">
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-info-circle text-primary"></i> Thông tin tổ chức & Lưu ý hành trình
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-slate-600">
                            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                                <h5 class="font-black text-slate-900 uppercase mb-2 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-rose-500"></i> Điểm đón khách
                                </h5>
                                <p class="m-0 leading-relaxed">Đón khách tại điểm hẹn trung tâm thành phố hoặc hỗ trợ đón tận nơi tại sảnh khách sạn khu vực trung tâm.</p>
                            </div>
                            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                                <h5 class="font-black text-slate-900 uppercase mb-2 flex items-center gap-2">
                                    <i class="fas fa-user-tie text-blue-500"></i> Hướng dẫn viên
                                </h5>
                                <p class="m-0 leading-relaxed">HDV chuyên nghiệp, nhiệt tình, theo suốt hành trình. Thuyết minh đa ngôn ngữ (Việt / Anh) theo yêu cầu đoàn.</p>
                            </div>
                            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                                <h5 class="font-black text-slate-900 uppercase mb-2 flex items-center gap-2">
                                    <i class="fas fa-suitcase-rolling text-amber-500"></i> Tiêu chuẩn hành lý
                                </h5>
                                <p class="m-0 py-0 leading-relaxed">Hành lý xách tay tiêu chuẩn 7kg và hành lý ký gửi theo quy định phương tiện vận chuyển của chương trình tour.</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(Str::contains(Str::lower($tour->title), ['du thuyền', 'cruise']))
                <!-- Special: Cruise Experience -->
                <div class="bg-dark rounded-[3.5rem] p-12 md:p-20 relative overflow-hidden group mb-16">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-teal-900/40"></div>
                    <img src="https://images.unsplash.com/photo-1548574505-5e239809ee19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" class="absolute inset-0 w-full h-full object-cover opacity-10 group-hover:scale-110 transition-transform duration-[10s]">
                    
                    <div class="relative z-10 space-y-12">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                            <div class="space-y-4">
                                <h5 class="text-primary text-[10px] font-black uppercase tracking-[0.4em]">#CRUISE EXPERIENCE</h5>
                                <h2 class="text-3xl md:text-5xl font-black text-white uppercase tracking-tighter leading-none">Trải nghiệm<br><span class="text-primary italic">Đẳng cấp 5 sao</span></h2>
                            </div>
                            <div class="px-8 py-6 bg-white/10 backdrop-blur-xl rounded-[2rem] border border-white/10">
                                <p class="text-white text-xs font-black uppercase tracking-widest mb-1">Hạng phòng tiêu chuẩn</p>
                                <p class="text-primary text-xl font-black uppercase tracking-tight">Suite Ocean View</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-primary border border-white/5">
                                    <i class="fas fa-bed text-xl"></i>
                                </div>
                                <h4 class="text-xs font-black text-white uppercase tracking-widest leading-tight">Cabin Rộng rãi<br><span class="text-slate-500 font-bold tracking-normal normal-case">Ban công riêng biệt</span></h4>
                            </div>
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-primary border border-white/5">
                                    <i class="fas fa-swimming-pool text-xl"></i>
                                </div>
                                <h4 class="text-xs font-black text-white uppercase tracking-widest leading-tight">Bể bơi vô cực<br><span class="text-slate-500 font-bold tracking-normal normal-case">Ngắm trọn cảnh vịnh</span></h4>
                            </div>
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-primary border border-white/5">
                                    <i class="fas fa-glass-cheers text-xl"></i>
                                </div>
                                <h4 class="text-xs font-black text-white uppercase tracking-widest leading-tight">Sunset Party<br><span class="text-slate-500 font-bold tracking-normal normal-case">Cocktail & Nhạc nhẹ</span></h4>
                            </div>
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-primary border border-white/5">
                                    <i class="fas fa-spa text-xl"></i>
                                </div>
                                <h4 class="text-xs font-black text-white uppercase tracking-widest leading-tight">Spa & Massage<br><span class="text-slate-500 font-bold tracking-normal normal-case">Thư giãn đỉnh cao</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab: Images -->
                <div x-show="activeTab === 'images'" class="animate-fade-in">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase mb-6">Bộ sưu tập hình ảnh</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($tour->images as $img)
                        <div class="relative aspect-square rounded-[2rem] overflow-hidden group cursor-zoom-in">
                            <img src="{{ $img->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700">
                            <div class="absolute inset-0 bg-dark/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i class="fas fa-expand text-white text-2xl"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tab: Reviews -->
                <div x-show="activeTab === 'reviews'" class="animate-fade-in space-y-12">
                     <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase mb-6">Đánh giá từ khách hàng</h3>
                     @if($tour->reviews->isEmpty())
                        <div class="p-12 text-center bg-slate-50 rounded-[3rem] border border-dashed border-slate-200">
                            <i class="far fa-comment-dots text-4xl text-slate-300 mb-4"></i>
                            <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Chưa có đánh giá nào cho tour này.</p>
                        </div>
                     @else
                        <div class="space-y-8">
                            @foreach($tour->reviews as $review)
                            <div class="flex gap-6 p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 hover:shadow-xl transition-all duration-500">
                                <img src="{{ $review->user->avatar_url }}" class="w-12 h-12 rounded-2xl object-cover shadow-md">
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="text-xs font-black text-slate-900 uppercase tracking-tighter">{{ $review->user->full_name }}</h5>
                                        <div class="flex gap-1">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="fas fa-star text-[10px] {{ $i <= $review->rating ? 'text-primary' : 'text-slate-200' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-slate-600 text-sm font-medium leading-relaxed italic">"{{ $review->comment }}"</p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-4">{{ $review->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                     @endif
                </div>

                <!-- Tab: Policy -->
                <div x-show="activeTab === 'policy'" class="animate-fade-in prose prose-slate max-w-none">
                     <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase mb-6">Chính sách & Điều khoản</h3>
                     <div class="space-y-12">
                        @if($tour->service_includes)
                        <div>
                            <h5 class="text-emerald-500 text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> Dịch vụ bao gồm
                            </h5>
                            <div class="prose prose-sm prose-emerald text-slate-600 font-medium ml-6">
                                {!! $tour->service_includes !!}
                            </div>
                        </div>
                        @endif

                        @if($tour->service_excludes)
                        <div>
                            <h5 class="text-rose-500 text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fas fa-times-circle"></i> Không bao gồm
                            </h5>
                            <div class="prose prose-sm prose-rose text-slate-600 font-medium ml-6">
                                {!! $tour->service_excludes !!}
                            </div>
                        </div>
                        @endif
                     </div>
                </div>

                <!-- Tab: FAQ -->
                <div x-show="activeTab === 'faq'" class="animate-fade-in space-y-8">
                     <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase mb-6">Câu hỏi thường gặp</h3>
                     <div class="space-y-4" x-data="{ openFaq: 1 }">
                        <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
                            <button @click="openFaq = openFaq === 1 ? 0 : 1" class="w-full flex items-center justify-between p-8 text-left group">
                                <span class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-primary transition-colors">Tôi có thể hủy tour và hoàn tiền không?</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform" :class="openFaq === 1 ? 'rotate-180 text-primary' : ''"></i>
                            </button>
                            <div x-show="openFaq === 1" x-collapse class="px-8 pb-8">
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">Bạn có thể hủy tour trước ít nhất 7 ngày khởi hành để được hoàn 100% chi phí. Hủy từ 3-6 ngày sẽ được hoàn 50%. Các trường hợp hủy dưới 3 ngày sẽ không được hoàn tiền.</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
                            <button @click="openFaq = openFaq === 2 ? 0 : 2" class="w-full flex items-center justify-between p-8 text-left group">
                                <span class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-primary transition-colors">Tour đã bao gồm vé máy bay chưa?</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform" :class="openFaq === 2 ? 'rotate-180 text-primary' : ''"></i>
                            </button>
                            <div x-show="openFaq === 2" x-collapse class="px-8 pb-8">
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">Giá tour hiển thị trên website là giá dịch vụ trên đất liền (land tour). Vé máy bay sẽ được tư vấn và đặt theo yêu cầu của quý khách để đảm bảo giờ bay phù hợp nhất.</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
                            <button @click="openFaq = openFaq === 3 ? 0 : 3" class="w-full flex items-center justify-between p-8 text-left group">
                                <span class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-primary transition-colors">Cần mang theo giấy tờ gì khi đi tour?</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform" :class="openFaq === 3 ? 'rotate-180 text-primary' : ''"></i>
                            </button>
                            <div x-show="openFaq === 3" x-collapse class="px-8 pb-8">
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">Quý khách vui lòng mang theo CMND/CCCD hoặc Hộ chiếu (còn hạn trên 6 tháng) bản gốc. Đối với trẻ em cần mang theo Bản sao giấy khai sinh.</p>
                            </div>
                        </div>
                     </div>
                </div>

                <!-- Tab: Map -->
                <div x-show="activeTab === 'map'" class="animate-fade-in">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase mb-6">Vị trí điểm đến</h3>
                    <div class="aspect-video rounded-[3rem] overflow-hidden bg-slate-100 border border-slate-100 relative group">
                        @if($tour->google_map)
                            <div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0">
                                {!! $tour->google_map !!}
                            </div>
                        @else
                            <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-dark/20 flex items-center justify-center hover:bg-dark/40 transition-all cursor-pointer">
                                <div class="bg-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-4">
                                    <i class="fas fa-map-marked-alt text-primary"></i>
                                    <span class="text-xs font-black text-slate-800 uppercase tracking-widest">Bản đồ đang cập nhật</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tab: Reviews -->
                <div x-show="activeTab === 'reviews'" class="animate-fade-in space-y-12">
                    <div class="flex flex-col md:flex-row gap-12 items-center bg-white p-12 rounded-[3.5rem] shadow-xl border border-slate-50">
                        <div class="text-center">
                            @php $avgRating = $tour->reviews->avg('rating') ?? 0; @endphp
                            <h4 class="text-6xl font-black text-slate-900 tracking-tighter mb-2">{{ number_format($avgRating, 1) }}</h4>
                            <div class="flex justify-center text-amber-400 text-sm mb-2">
                                @for($i=1; $i<=5; $i++)
                                    <i class="{{ $i <= round($avgRating) ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Dựa trên {{ $tour->reviews->count() }} đánh giá</p>
                        </div>
                        
                        <div class="flex-1 space-y-3 w-full">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                @php 
                                    $count = $tour->reviews->where('rating', $star)->count();
                                    $percent = $tour->reviews->count() > 0 ? ($count / $tour->reviews->count()) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-4">
                                    <span class="text-[9px] font-black text-slate-400 w-4">{{ $star }}</span>
                                    <div class="flex-1 h-2 bg-slate-50 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-[9px] font-black text-slate-400 w-8">{{ round($percent) }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @auth
                        @php 
                            $hasBooked = \App\Models\Booking::where('user_id', Auth::id())
                                ->whereHas('departure', function($q) use ($tour) { $q->where('tour_id', $tour->id); })
                                ->where('status', 'Completed')->exists();
                            $hasReviewed = $tour->reviews->where('user_id', Auth::id())->first();
                        @endphp

                        @if($hasBooked && !$hasReviewed)
                        <div class="bg-white p-10 md:p-16 rounded-[4rem] shadow-2xl border border-slate-100 relative overflow-hidden">
                            <h4 class="text-2xl font-black text-slate-900 tracking-tighter uppercase mb-10">Chia sẻ trải nghiệm của bạn</h4>
                            <form action="{{ route('public.reviews.store', $tour->id) }}" method="POST" class="space-y-8">
                                @csrf
                                <div class="space-y-3" x-data="{ currentRating: 5 }">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Mức độ hài lòng</label>
                                    <input type="hidden" name="rating" :value="currentRating">
                                    <div class="flex gap-3 text-2xl">
                                        @for($i=1; $i<=5; $i++)
                                        <button type="button" @click="currentRating = {{ $i }}" :class="currentRating >= {{ $i }} ? 'text-amber-400' : 'text-slate-200'" class="transition-colors scale-125">
                                            <i class="fas fa-star"></i>
                                        </button>
                                        @endfor
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Lời nhận xét</label>
                                    <textarea name="comment" rows="4" placeholder="Cảm nhận của bạn về chuyến đi..." class="form-control rounded-3xl py-6 px-8"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary px-12 py-5 rounded-3xl shadow-xl shadow-emerald-500/20">Gửi nhận xét ngay</button>
                            </form>
                        </div>
                        @elseif($hasReviewed)
                             <div class="p-8 bg-emerald-50 rounded-3xl border border-emerald-100 text-center">
                                <p class="text-xs font-black text-emerald-600 uppercase tracking-widest">Cảm ơn bạn đã tham gia đánh giá hành trình này!</p>
                             </div>
                        @else
                            <div class="p-8 bg-slate-50 rounded-3xl border border-dashed border-slate-200 text-center">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-loose">Bạn chỉ có thể đánh giá sau khi hoàn thành chuyến đi cùng {{ $settings['site_name'] ?? 'Tour Travel' }}.</p>
                             </div>
                        @endif
                    @else
                         <div class="p-8 bg-slate-900 rounded-3xl text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Bạn muốn để lại đánh giá?</p>
                            <a href="{{ route('login') }}" class="text-xs font-black text-white uppercase tracking-widest border-b border-primary pb-1">Vui lòng đăng nhập</a>
                         </div>
                    @endauth

                    <div class="space-y-8 pt-8 border-t border-slate-100">
                        @forelse($tour->reviews as $review)
                            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 animate-fade-in">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="flex items-center gap-4">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->full_name) }}&background=E2E8F0&color=64748B" class="w-14 h-14 rounded-2xl object-cover shadow-sm">
                                        <div>
                                            <h5 class="text-sm font-black text-slate-900 uppercase tracking-tighter">{{ $review->user->full_name }}</h5>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $review->created_at->format('d / m / Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-amber-400 text-xs flex gap-0.5">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-slate-600 text-sm font-medium leading-relaxed italic border-l-4 border-emerald-50 pl-6 ml-1">"{{ $review->comment }}"</p>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic opacity-50">Chưa có đánh giá nào cho tour này.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Side: Booking Sidepanel -->
            <div class="lg:col-span-1">
                <div class="space-y-8">
                    
                    <!-- Pricing Card -->
                    <div class="card p-10 bg-dark text-white border-b-8 border-b-primary shadow-2xl overflow-hidden relative group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-[60px] -mr-16 -mt-16"></div>
                        <div class="relative z-10">
                            <p class="text-primary text-[10px] font-black uppercase tracking-[0.4em] mb-4">Giá ưu đãi từ</p>
                            <h3 class="text-5xl font-black tracking-tighter text-white mb-2">{{ number_format($tour->base_price) }}đ</h3>
                            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest border-b border-white/5 pb-8 mb-8 italic">Giá trọn gói một khách (đã gồm thuế)</p>
                            
                            <form action="{{ route('public.bookings.checkout', ['departure' => 0]) }}" method="GET" x-data="{ departureId: '', showError: false }" @submit.prevent="if(!departureId) { showError = true; } else { window.location.href = '/booking/checkout/' + departureId }">
                                <div class="space-y-6">
                                    <div class="form-group mb-0">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Chọn ngày khởi hành:</label>
                                        <div class="relative group">
                                            <select id="booking-form-select" x-model="departureId" @change="showError = false" class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-xs font-bold text-white uppercase tracking-widest focus:ring-2 focus:ring-primary appearance-none transition-all">
                                                <option value="" class="bg-dark">Vui lòng chọn ngày</option>
                                                @foreach($tour->departures as $dep)
                                                    <option value="{{ $dep->id }}" class="bg-dark">
                                                        {{ date('d / m / Y', strtotime($dep->start_date)) }} 
                                                        ({{ number_format($dep->price_override ?? $tour->base_price) }}đ)
                                                        - Còn {{ $dep->available_seats }} chỗ
                                                    </option>
                                                @endforeach
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-primary group-hover:translate-y-0 transition-transform"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 pt-4">
                                        <div class="bg-white/5 p-4 rounded-2xl border border-white/5 text-center">
                                            <p class="text-[9px] font-black text-slate-500 uppercase mb-1">Thời gian</p>
                                            <p class="text-xs font-black uppercase tracking-tight text-primary">{{ $tour->duration }}</p>
                                        </div>
                                        <div class="bg-white/5 p-4 rounded-2xl border border-white/5 text-center">
                                            <p class="text-[9px] font-black text-slate-500 uppercase mb-1">Mã Tour</p>
                                            <p class="text-xs font-black uppercase tracking-tight text-white">#T{{ str_pad($tour->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>

                                    <div x-show="showError" x-transition style="display: none;" class="mt-4 p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-500 text-xs font-bold text-center">
                                        Vui lòng chọn ngày khởi hành để đặt tour
                                    </div>
                                    <button type="submit" class="btn btn-primary w-full py-5 rounded-3xl mt-6 !text-xs group">
                                        ĐẶT TOUR NGAY <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Hotline Support -->
                    <div class="bg-primary/5 p-10 rounded-[2.5rem] border border-primary/10 text-center relative overflow-hidden group">
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-primary mx-auto mb-6 shadow-xl shadow-primary/10">
                                <i class="fas fa-phone-alt text-2xl"></i>
                            </div>
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-2">Hỗ trợ tư vấn trực tiếp</h4>
                            <p class="text-2xl font-black text-primary tracking-tighter">{{ $settings['contact_phone'] ?? '0123 456 789' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-4">Hotline hỗ trợ 24/7 hoàn toàn miễn phí</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Related Tours -->
<section class="py-24 bg-slate-50 border-t border-slate-100">
    <div class="container mb-16">
        <div class="flex items-center gap-3">
            <span class="w-8 h-[2px] bg-primary"></span>
            <p class="text-primary text-[10px] font-black uppercase tracking-[0.4em]">Đề xuất cho bạn</p>
        </div>
        <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter uppercase mt-4">Có thể bạn <span class="text-primary italic">quan tâm</span></h2>
    </div>
    
    <div class="container grid grid-cols-1 md:grid-cols-4 gap-8">
        @foreach($related_tours as $rt)
        <a href="{{ route('public.tours.show', $rt->slug) }}" class="group bg-white rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col">
            <div class="relative aspect-video overflow-hidden">
                <img src="{{ $rt->images->first() ? $rt->images->first()->image_url : 'https://placehold.co/600x400' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            </div>
            <div class="p-6 flex-1 flex flex-col">
                <h4 class="text-sm font-black text-dark uppercase tracking-tight line-clamp-2 leading-tight mb-4 group-hover:text-primary transition-colors">{{ $rt->title }}</h4>
                <div class="mt-auto flex justify-between items-center pt-4 border-t border-slate-50">
                    <p class="text-xs font-black text-primary">{{ number_format($rt->base_price) }}đ</p>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $rt->duration }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
<!-- Mobile Sticky Booking Bar -->
<div class="fixed bottom-0 left-0 right-0 z-[60] lg:hidden bg-white/95 backdrop-blur-md border-t border-slate-100 p-4 shadow-2xl safe-area-pb">
    <div class="container flex items-center justify-between gap-6 px-4">
        <div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Giá chỉ từ</p>
            <p class="text-xl font-black text-primary leading-none">{{ number_format($tour->base_price) }}đ</p>
        </div>
        <button onclick="document.querySelector('#booking-form-select')?.focus(); document.querySelector('#booking-form-select')?.scrollIntoView({behavior: 'smooth', block: 'center'});" class="btn btn-primary flex-1 py-4 !text-[10px] rounded-2xl shadow-lg shadow-emerald-500/20">
            CHỌN NGÀY & ĐẶT
        </button>
    </div>
</div>
@endsection

@section('styles')
<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
</style>
@endsection
