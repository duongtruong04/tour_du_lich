@extends('layouts.app')

@section('title', 'Khuyến Mãi & Ưu Đãi')

@section('content')
<!-- Header -->
<header class="breadcrumb-header min-h-[45vh] flex flex-col justify-center text-center">
    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" class="bg-image">
    <div class="overlay" style="background: linear-gradient(to bottom, rgba(15,23,42,0.3), rgba(5,150,105,0.25), rgba(15,23,42,0.85)); opacity: 1;"></div>
    <div class="container relative z-10">
        <nav class="breadcrumb-nav justify-center mb-12">
            <a href="{{ route('home') }}">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <span class="text-white">Khuyến Mãi</span>
        </nav>
        <h1 class="text-4xl md:text-7xl font-black text-white tracking-tighter uppercase mb-4 leading-none">
            Ưu Đãi <span class="text-primary italic">Đặc Biệt</span>
        </h1>
        <p class="text-teal-100 text-[10px] md:text-xs font-black uppercase tracking-[0.4em] opacity-80">Khám phá thế giới với mức giá tốt nhất</p>
    </div>
</header>

<!-- Promotions List -->
<section class="py-24 bg-slate-50 -mt-24 relative z-20">
    <div class="container">
        @if($promotions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($promotions as $promo)
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $promo->image_path ? asset('storage/'.$promo->image_path) : 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark/80 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end">
                            <div>
                                <span class="px-3 py-1 bg-primary text-white text-[9px] font-black uppercase tracking-widest rounded-lg">
                                    {{ $promo->discount_type == 'Percentage' ? 'Giảm '.$promo->discount_value.'%' : 'Giảm '.number_format($promo->discount_value).'đ' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 md:p-8">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><i class="far fa-clock"></i> HSD: {{ date('d/m/Y', strtotime($promo->expiry_date)) }}</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight mb-3 line-clamp-2 group-hover:text-primary transition-colors">{{ $promo->title }}</h3>
                        <p class="text-sm text-slate-500 mb-6 line-clamp-3 leading-relaxed">{{ $promo->description }}</p>
                        
                        <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Mã khuyến mãi</span>
                                <div class="bg-slate-50 px-3 py-2 rounded-xl border border-slate-200 inline-block font-mono font-bold text-primary tracking-widest text-center select-all">
                                    {{ $promo->code }}
                                </div>
                            </div>
                            <button class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center hover:bg-primary hover:text-white transition-colors" onclick="copyPromo('{{ $promo->code }}')" title="Sao chép mã">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-16 flex justify-center">
                {{ $promotions->links() }}
            </div>
        @else
            <div class="bg-white p-16 rounded-[2rem] shadow-sm border border-slate-100 text-center max-w-2xl mx-auto">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-6">
                    <i class="fas fa-ticket-alt text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 uppercase mb-3">Chưa có khuyến mãi nào</h3>
                <p class="text-slate-500">Hiện tại chúng tôi chưa có chương trình khuyến mãi nào. Vui lòng quay lại sau nhé!</p>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    function copyPromo(code) {
        navigator.clipboard.writeText(code).then(() => {
            alert('Đã sao chép mã khuyến mãi: ' + code);
        });
    }
</script>
@endsection
