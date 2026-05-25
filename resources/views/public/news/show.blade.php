@extends('layouts.app')

@section('title', $news->title)

@section('content')
<!-- Breadcrumb Header -->
<header class="breadcrumb-header text-center min-h-[50vh] flex flex-col justify-center">
    <img src="{{ $news->image_url }}" class="bg-image">
    <div class="overlay"></div>
    <div class="container relative z-10">
        <nav class="breadcrumb-nav justify-center mb-12">
            <a href="{{ route('home') }}">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ route('public.news.index') }}">Cẩm nang du lịch</a>
            <i class="fas fa-chevron-right"></i>
            <span class="text-white">Chi tiết tin tức</span>
        </nav>
        <p class="text-primary text-[10px] font-black uppercase tracking-[0.6em] mb-6 animate-fade-in-down">#{{ $news->category->name }}</p>
        <h1 class="text-3xl md:text-6xl font-black text-white tracking-tighter leading-tight uppercase max-w-5xl mx-auto mb-8">{{ $news->title }}</h1>
        <div class="flex flex-wrap items-center justify-center gap-6 text-[10px] font-black uppercase tracking-widest text-slate-400">
            <span class="flex items-center gap-2"><i class="far fa-folder-open text-primary"></i> {{ $news->category->name }}</span>
            <span class="w-[1px] h-4 bg-white/10 hidden md:block"></span>
            <span class="flex items-center gap-2"><i class="far fa-calendar-alt text-primary"></i> {{ $news->created_at->format('d M, Y') }}</span>
            <span class="w-[1px] h-4 bg-white/10 hidden md:block"></span>
            <span class="flex items-center gap-2"><i class="far fa-user text-primary"></i> {{ $news->author->full_name }}</span>
            <span class="w-[1px] h-4 bg-white/10 hidden md:block"></span>
            <span class="flex items-center gap-2"><i class="far fa-clock text-amber-400"></i> ~3 phút đọc</span>
            <span class="w-[1px] h-4 bg-white/10 hidden md:block"></span>
            <span class="flex items-center gap-2"><i class="far fa-eye text-primary"></i> {{ number_format($news->view_count) }} lượt xem</span>
        </div>
    </div>
</header>

<!-- Article Content -->
<section class="py-24 bg-white">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            
            <!-- Left Side: Main Body -->
            <div class="lg:col-span-2">
                <div class="prose prose-slate prose-lg max-w-none text-slate-700 font-medium leading-[2]">
                    @if($news->image_path)
                        <img src="{{ $news->image_url }}" class="w-full rounded-[3rem] shadow-2xl mb-16 border border-slate-50">
                    @endif
                    
                    <div class="article-body">
                        {!! $news->content !!}
                    </div>
                </div>

                <!-- Footer / Share -->
                <div class="mt-20 pt-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="flex items-center gap-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Thẻ bài viết:</span>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-slate-50 rounded-lg text-[8px] font-black text-slate-500 uppercase tracking-widest">#Du-Lịch</span>
                            <span class="px-3 py-1 bg-slate-50 rounded-lg text-[8px] font-black text-slate-500 uppercase tracking-widest">#{{ $news->category->slug }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Chia sẻ:</span>
                        <div class="flex gap-3">
                            <a href="#" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all"><i class="fab fa-facebook-f text-sm"></i></a>
                            <a href="#" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all"><i class="fab fa-twitter text-sm"></i></a>
                            <a href="#" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all"><i class="fas fa-link text-sm"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Sidebar -->
            <div class="lg:col-span-1">
                <div class="space-y-12">
                    
                    <!-- Author Card -->
                    <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100 text-center">
                        <img src="{{ $news->author->avatar_url }}" class="w-20 h-20 rounded-3xl mx-auto mb-6 shadow-xl border-4 border-white">
                        <h4 class="text-sm font-black text-dark uppercase tracking-widest leading-none mb-2">{{ $news->author->full_name }}</h4>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-4">Biên tập viên cấp cao</p>
                        <div class="mb-6 p-3 bg-white rounded-2xl border border-slate-100 text-[10px] text-slate-600 font-bold flex items-center justify-center gap-2">
                            <i class="fas fa-envelope text-primary"></i> {{ $news->author->email ?? 'contact@antigravity.vn' }}
                        </div>
                        <p class="text-xs font-medium text-slate-500 leading-relaxed italic mb-8">"Chia sẻ những hành trình thú vị và cảm hứng xê dịch cho cộng đồng yêu du lịch."</p>
                        <a href="{{ route('public.news.index', ['author' => $news->author->id]) }}" class="btn btn-primary w-full !text-[9px] !py-4">Xem tất cả bài viết</a>
                    </div>

                    <!-- Related News -->
                    <div>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary mb-8 border-l-4 border-primary pl-4">Bài viết liên quan</h4>
                        <div class="space-y-8">
                            @foreach($related_news as $rn)
                            <a href="{{ route('public.news.show', $rn->slug) }}" class="flex gap-4 group">
                                <div class="w-20 h-20 bg-slate-100 rounded-2xl overflow-hidden flex-shrink-0 border border-slate-100">
                                    <img src="{{ $rn->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div>
                                    <h5 class="text-xs font-black text-dark uppercase tracking-tight leading-tight line-clamp-2 group-hover:text-primary transition-colors">{{ $rn->title }}</h5>
                                    <p class="text-[9px] font-bold text-slate-400 mt-2">{{ $rn->created_at->format('d/m/Y') }}</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recommended Tours -->
                    @if($recommended_tours->count() > 0)
                    <div class="bg-dark p-10 rounded-[3rem] border border-white/5 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-primary/5 opacity-50"></div>
                        <div class="relative z-10">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary mb-8 border-l-4 border-primary pl-4">Tour gợi ý cho bạn</h4>
                            <div class="space-y-6">
                                @foreach($recommended_tours as $rt)
                                <a href="{{ route('public.tours.show', $rt->slug) }}" class="block bg-white/5 rounded-3xl overflow-hidden border border-white/5 hover:bg-white/10 transition-all group/tour">
                                    <div class="aspect-video overflow-hidden">
                                        <img src="{{ $rt->images->first() ? $rt->images->first()->image_url : 'https://placehold.co/400x300' }}" class="w-full h-full object-cover group-hover/tour:scale-110 transition-transform duration-700">
                                    </div>
                                    <div class="p-6">
                                        <h5 class="text-xs font-black text-white uppercase tracking-tight leading-tight line-clamp-2 mb-3">{{ $rt->title }}</h5>
                                        <div class="flex justify-between items-center">
                                            <p class="text-[10px] font-bold text-primary">{{ number_format($rt->base_price) }}đ</p>
                                            <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Khám phá <i class="fas fa-arrow-right ml-1"></i></span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Newsletter Banner -->
                    <div class="bg-primary p-12 rounded-[3rem] text-dark shadow-2xl shadow-primary/20 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-white/10 group-hover:bg-white/20 transition-all"></div>
                        <div class="relative z-10">
                            <i class="fas fa-paper-plane text-4xl mb-8 block"></i>
                            <h4 class="text-2xl font-black tracking-tighter uppercase mb-6 leading-none italic">Đừng bỏ lỡ<br>Cảm hứng mới!</h4>
                            <p class="text-xs font-bold uppercase tracking-tight mb-8 opacity-60">Nhận bản tin du lịch hàng đầu.</p>
                            <form @submit.prevent="alert('Cảm ơn bạn! Đăng ký bản tin thành công.')" class="space-y-4">
                                <input type="email" required placeholder="Email của bạn..." class="w-full bg-white/20 border-white/30 rounded-2xl px-6 py-4 text-xs font-bold text-dark placeholder-dark/40 focus:ring-1 focus:ring-dark transition-all">
                                <button type="submit" class="w-full py-4 bg-dark text-white rounded-2xl font-black text-[9px] uppercase tracking-widest hover:shadow-2xl transition-all">Gửi thông tin</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .article-body h2 { @apply text-2xl font-black text-slate-900 uppercase tracking-tighter mt-12 mb-6; }
    .article-body h3 { @apply text-xl font-black text-slate-900 uppercase tracking-tighter mt-10 mb-5; }
    .article-body p { @apply text-lg font-medium text-slate-700 leading-loose mb-8; }
    .article-body img { @apply rounded-3xl shadow-xl my-12; }
    .article-body ul { @apply list-disc pl-10 mb-8 space-y-4; }
    .article-body li { @apply text-lg font-medium text-slate-700 leading-loose; }
    .article-body blockquote { @apply border-l-8 border-primary pl-8 py-4 bg-slate-50 rounded-r-3xl text-xl italic font-serif text-slate-600 my-10; }
</style>
@endsection
