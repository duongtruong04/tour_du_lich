<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $settings['site_name'] ?? 'Tour Du Lịch') -
        {{ $settings['site_title'] ?? 'Trải nghiệm chuyên nghiệp' }}</title>

    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Tailwind for utilities, but our style.css for design -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#10b981',
                        secondary: '#f97316',
                        dark: '#0f172a',
                    }
                }
            }
        }
    </script>

    <!-- App Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    @yield('styles')

    <!-- Alpine.js Intersect -->
    <script defer src="https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased font-sans bg-slate-50 text-slate-900">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 transition-all duration-300 glass" x-data="{ open: false, scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/20">
                        <i class="fas fa-paper-plane text-white text-lg"></i>
                    </div>
                    <div>
                        <span
                            class="text-xl font-black text-dark tracking-tighter uppercase">{{ $settings['logo_text'] ?? 'ANTIGRAVITY' }}</span>
                        <p class="text-[8px] font-bold text-teal-600 uppercase tracking-[0.3em] -mt-1">Premium Travel
                        </p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'text-primary font-bold' : 'hover:text-primary transition-colors' }}">Trang
                        chủ</a>
                    <a href="{{ route('public.destinations.index') }}"
                        class="nav-link {{ request()->routeIs('public.destinations.*') ? 'text-primary font-bold' : 'hover:text-primary transition-colors' }}">Điểm
                        đến</a>
                    <a href="{{ route('public.tours.index') }}"
                        class="nav-link {{ request()->routeIs('public.tours.*') ? 'text-primary font-bold' : 'hover:text-primary transition-colors' }}">Tour
                        du lịch</a>
                    <a href="{{ route('public.news.index') }}"
                        class="nav-link {{ request()->routeIs('public.news.*') ? 'text-primary font-bold' : 'hover:text-primary transition-colors' }}">Tin
                        tức</a>
                    <a href="{{ route('public.promotions.index') }}"
                        class="nav-link flex items-center gap-1 {{ request()->routeIs('public.promotions.*') ? 'text-primary font-bold' : 'text-rose-500 hover:text-rose-600 transition-colors' }}">
                        Khuyến mãi <span class="relative flex h-2 w-2"><span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span><span
                                class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span></span>
                    </a>

                    @auth
                        <div class="relative" x-data="{ openUser: false }">
                            <button @click="openUser = !openUser"
                                class="flex items-center gap-2 group p-1 pr-3 bg-white rounded-full border border-slate-100 hover:border-primary transition-all">
                                <img src="{{ Auth::user()->avatar_url }}"
                                    class="w-8 h-8 rounded-full object-cover">
                                <span class="text-xs font-bold text-slate-700">{{ Auth::user()->full_name }}</span>
                                <i class="fas fa-chevron-down text-[10px] text-slate-400 group-hover:text-primary"></i>
                            </button>
                            <!-- Dropdown -->
                            <div x-show="openUser" @click.away="openUser = false" x-transition
                                class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 overflow-hidden">
                                <a href="{{ route('public.account.profile') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary transition-all">
                                    <i class="fas fa-user-circle text-slate-400"></i> Hồ sơ cá nhân
                                </a>
                                <a href="{{ route('public.account.bookings.history') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary transition-all">
                                    <i class="fas fa-history text-slate-400"></i> Lịch sử đặt tour
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary transition-all">
                                        <i class="fas fa-th-large text-slate-400"></i> Trang quản trị
                                    </a>
                                @endif
                                <div class="border-t border-slate-50 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-xs font-bold text-red-500 hover:bg-red-50 transition-all text-left">
                                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}"
                                class="btn btn-outline text-xs scale-90 border-slate-200 text-slate-600">Đăng nhập</a>
                            <a href="{{ route('register') }}" class="btn btn-primary text-xs scale-90">Đăng ký</a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open"
                        class="text-slate-500 hover:text-primary focus:outline-none p-2 rounded-xl bg-slate-100">
                        <i class="fas fa-bars text-xl" x-show="!open"></i>
                        <i class="fas fa-times text-xl" x-show="open"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            class="md:hidden bg-white border-t border-slate-50 shadow-xl overflow-hidden">
            <div class="px-6 py-8 space-y-6">
                <!-- Links -->
                <div class="space-y-4">
                    <a href="{{ route('home') }}"
                        class="flex items-center gap-4 text-sm font-black text-slate-700 uppercase tracking-widest hover:text-primary transition-all {{ request()->routeIs('home') ? 'text-primary' : '' }}">
                        <i class="fas fa-home w-5 text-center text-lg"></i> Trang chủ
                    </a>
                    <a href="{{ route('public.destinations.index') }}"
                        class="flex items-center gap-4 text-sm font-black text-slate-700 uppercase tracking-widest hover:text-primary transition-all {{ request()->routeIs('public.destinations.*') ? 'text-primary' : '' }}">
                        <i class="fas fa-map-marker-alt w-5 text-center text-lg"></i> Điểm đến
                    </a>
                    <a href="{{ route('public.tours.index') }}"
                        class="flex items-center gap-4 text-sm font-black text-slate-700 uppercase tracking-widest hover:text-primary transition-all {{ request()->routeIs('public.tours.*') ? 'text-primary' : '' }}">
                        <i class="fas fa-route w-5 text-center text-lg"></i> Tour du lịch
                    </a>
                    <a href="{{ route('public.news.index') }}"
                        class="flex items-center gap-4 text-sm font-black text-slate-700 uppercase tracking-widest hover:text-primary transition-all {{ request()->routeIs('public.news.*') ? 'text-primary' : '' }}">
                        <i class="fas fa-newspaper w-5 text-center text-lg"></i> Tin tức
                    </a>
                    <a href="{{ route('public.promotions.index') }}"
                        class="flex items-center gap-4 text-sm font-black text-rose-500 uppercase tracking-widest hover:text-rose-600 transition-all {{ request()->routeIs('public.promotions.*') ? 'text-rose-600' : '' }}">
                        <i class="fas fa-tags w-5 text-center text-lg"></i> Khuyến mãi
                    </a>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    @auth
                        <div class="flex items-center gap-4 mb-6 p-4 bg-slate-50 rounded-2xl">
                            <img src="{{ Auth::user()->avatar_url }}"
                                class="w-12 h-12 rounded-full object-cover shadow-md">
                            <div>
                                <p class="text-xs font-black text-slate-900 uppercase tracking-tight">
                                    {{ Auth::user()->full_name }}</p>
                                <p class="text-[9px] font-bold text-primary uppercase tracking-widest mt-1">Thành viên</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-6">
                            <a href="{{ route('public.account.bookings.history') }}"
                                class="flex items-center gap-4 text-sm font-black text-slate-700 uppercase tracking-widest hover:text-primary transition-all">
                                <i class="fas fa-history text-primary"></i> Tra cứu đơn hàng
                            </a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center gap-4 text-sm font-black text-slate-700 uppercase tracking-widest hover:text-primary transition-all">
                                    <i class="fas fa-th-large w-5 text-center text-lg text-slate-400"></i> Trang quản trị
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-slate-50">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-4 text-sm font-black text-rose-500 uppercase tracking-widest hover:text-rose-600 transition-all">
                                    <i class="fas fa-sign-out-alt w-5 text-center text-lg"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('login') }}"
                                class="btn btn-outline border-slate-200 text-slate-500 py-4 !text-[10px] rounded-2xl">Đăng
                                nhập</a>
                            <a href="{{ route('register') }}" class="btn btn-primary py-4 !text-[10px] rounded-2xl">Đăng
                                ký</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark pt-24 pb-12 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <!-- Branding -->
                <div class="space-y-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center">
                            <i class="fas fa-paper-plane text-white text-xl"></i>
                        </div>
                        <span
                            class="text-2xl font-black tracking-tighter uppercase">{{ $settings['logo_text'] ?? 'ANTIGRAVITY' }}</span>
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed italic">
                        "{{ $settings['site_title'] ?? 'Cung cấp những hành trình tuyệt vời, mở ra những trải nghiệm vô tận cho bạn và gia đình.' }}"
                    </p>
                    <div class="flex gap-4">
                        <a href="{{ $settings['facebook_url'] ?? '#' }}"
                            target="_blank" rel="noopener noreferrer" aria-label="Facebook TourTravel"
                            class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center hover:bg-primary transition-all"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="{{ $settings['instagram_url'] ?? '#' }}"
                            target="_blank" rel="noopener noreferrer" aria-label="Instagram TourTravel"
                            class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center hover:bg-primary transition-all"><i
                                class="fab fa-instagram"></i></a>
                        <a href="{{ $settings['twitter_url'] ?? '#' }}"
                            target="_blank" rel="noopener noreferrer" aria-label="Twitter TourTravel"
                            class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center hover:bg-primary transition-all"><i
                                class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.3em] text-primary mb-8">Liên kết nhanh</h4>
                    <ul class="space-y-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <li><a href="#" class="hover:text-primary">Giới thiệu</a></li>
                        <li><a href="{{ route('public.tours.index') }}" class="hover:text-primary">Tour nổi bật</a></li>
                        <li><a href="{{ route('public.news.index') }}" class="hover:text-primary">Cẩm nang du lịch</a>
                        </li>
                        <li><a href="#" class="hover:text-primary">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-primary">Điều khoản dịch vụ</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.3em] text-primary mb-8">Thông tin liên hệ</h4>
                    <ul class="space-y-6 text-xs font-bold text-slate-300">
                        <li class="flex gap-4">
                            <i class="fas fa-phone-alt text-primary text-base"></i>
                            <span>{{ $settings['contact_phone'] ?? '0123 456 789' }}</span>
                        </li>
                        <li class="flex gap-4">
                            <i class="fas fa-envelope text-primary text-base"></i>
                            <span>{{ $settings['contact_email'] ?? 'contact@antigravity.vn' }}</span>
                        </li>
                        <li class="flex gap-4">
                            <i class="fas fa-map-marker-alt text-primary text-base"></i>
                            <span
                                class="leading-relaxed">{{ $settings['address'] ?? '123 Đường Du Lịch, Quận 1, TP. Hồ Chí Minh' }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.3em] text-primary mb-8">Đăng ký nhận tin</h4>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-loose mb-6 italic">
                        Đăng ký để không bỏ lỡ các ưu đãi tour du lịch hấp dẫn nhất từ chúng tôi.
                    </p>
                    <div class="flex flex-col gap-3">
                        <input type="email" placeholder="Nhập email của bạn..."
                            class="bg-white/5 border-none rounded-2xl px-5 py-4 text-xs font-bold focus:ring-2 focus:ring-primary transition-all">
                        <button class="btn btn-primary">Đăng ký ngay</button>
                    </div>
                </div>
            </div>

            <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[10px] font-black uppercase tracking-[0.5em] text-slate-700 italic">
                    &copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'ANTIGRAVITY TRAVEL' }}. All rights reserved.
                </p>
                <div class="flex gap-6 text-[10px] font-bold uppercase tracking-widest text-slate-600">
                    <span>Thanh toán an toàn với:</span>
                    <i class="fab fa-cc-visa text-lg"></i>
                    <i class="fab fa-cc-mastercard text-lg"></i>
                    <i class="fas fa-credit-card text-lg"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- AI Chatbot Widget -->
    <div x-data="{ 
        isOpen: false, 
        messages: [
            { sender: 'ai', text: 'Xin chào! Tôi là Trợ lý ảo AI của {{ $settings['site_name'] ?? 'Travel' }}. Tôi có thể giúp gì cho bạn về các tour du lịch, lịch khởi hành hay chương trình khuyến mãi hôm nay?' }
        ], 
        inputMessage: '', 
        isLoading: false,
        async sendMessage() {
            if (!this.inputMessage.trim() || this.isLoading) return;
            
            const question = this.inputMessage;
            this.messages.push({ sender: 'user', text: question });
            this.inputMessage = '';
            this.isLoading = true;

            this.$nextTick(() => {
                this.scrollToBottom();
            });

            try {
                const response = await fetch('{{ route('public.chat.ask') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ question: question })
                });

                const data = await response.json();
                if (response.ok) {
                    this.messages.push({ sender: 'ai', text: data.answer });
                } else {
                    this.messages.push({ sender: 'ai', text: data.error || 'Có lỗi xảy ra, vui lòng thử lại.' });
                }
            } catch (error) {
                this.messages.push({ sender: 'ai', text: 'Không thể kết nối đến máy chủ AI. Vui lòng thử lại sau.' });
            } finally {
                this.isLoading = false;
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            }
        },
        scrollToBottom() {
            const container = this.$refs.chatContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },
        init() {
            @auth
                this.loadChatHistory();
            @endauth
        },
        async loadChatHistory() {
            try {
                const response = await fetch('{{ route('public.chat.history') }}');
                if (response.ok) {
                    const data = await response.json();
                    if (data && data.length > 0) {
                        const historyMessages = [];
                        data.forEach(chat => {
                            historyMessages.push({ sender: 'user', text: chat.message });
                            historyMessages.push({ sender: 'ai', text: chat.reply });
                        });
                        this.messages = historyMessages;
                    }
                }
            } catch (error) {
                console.error('Failed to load chat history:', error);
            }
        }
    }" class="fixed bottom-6 right-6 z-[999]">

        <!-- Toggle Button -->
        <button @click="isOpen = !isOpen; if(isOpen) { $nextTick(() => scrollToBottom()) }"
            class="w-16 h-16 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-600 text-white flex items-center justify-center shadow-lg hover:shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300 relative group">
            <span class="absolute -top-1 -right-1 flex h-4 w-4" x-show="!isOpen">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 border-2 border-white"></span>
            </span>
            <i class="fas fa-comment-dots text-2xl group-hover:rotate-12 transition-transform duration-300"
                x-show="!isOpen"></i>
            <i class="fas fa-times text-2xl transition-transform duration-300" x-show="isOpen"></i>
        </button>

        <!-- Chat Container Panel -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-10 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-10 scale-95"
            class="absolute bottom-20 right-0 w-[360px] max-w-[calc(100vw-2rem)] h-[500px] bg-white/95 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-100 flex flex-col overflow-hidden"
            style="display: none;">

            <!-- Chat Header -->
            <div class="p-6 bg-dark text-white flex items-center justify-between border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/20 rounded-xl flex items-center justify-center text-primary">
                        <i class="fas fa-robot text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider leading-none">
                            {{ $settings['site_name'] ?? 'Travel' }} AI</h4>
                        <p
                            class="text-[9px] font-bold text-emerald-400 mt-1 uppercase tracking-widest flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span> Trực tuyến
                        </p>
                    </div>
                </div>
                <button @click="isOpen = false" class="text-slate-400 hover:text-white transition-colors">
                    <i class="fas fa-minus"></i>
                </button>
            </div>

            <!-- Messages Area -->
            <div x-ref="chatContainer" class="flex-1 p-6 overflow-y-auto space-y-4 no-scrollbar bg-slate-50/50">
                <template x-for="message in messages">
                    <div :class="message.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="message.sender === 'user' 
                                ? 'bg-primary text-white rounded-t-2xl rounded-bl-2xl shadow-md shadow-emerald-500/10' 
                                : 'bg-white text-slate-800 rounded-t-2xl rounded-br-2xl border border-slate-100 shadow-sm'"
                            class="max-w-[85%] px-4 py-3 text-xs font-medium leading-relaxed whitespace-pre-wrap"
                            x-text="message.text">
                        </div>
                    </div>
                </template>

                <!-- Loading Bubble -->
                <div class="flex justify-start" x-show="isLoading">
                    <div
                        class="bg-white text-slate-800 rounded-t-2xl rounded-br-2xl border border-slate-100 shadow-sm px-4 py-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce"
                            style="animation-delay: 0.1s"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce"
                            style="animation-delay: 0.2s"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce"
                            style="animation-delay: 0.3s"></span>
                    </div>
                </div>
            </div>

            <!-- Chat Footer Input -->
            <form @submit.prevent="sendMessage" class="p-4 bg-white border-t border-slate-100 flex items-center gap-3">
                <input type="text" x-model="inputMessage" placeholder="Hỏi trợ lý AI về các tour..."
                    class="flex-1 bg-slate-50 border-none rounded-2xl px-5 py-3.5 text-xs font-bold placeholder-slate-400 focus:ring-2 focus:ring-primary/20 transition-all">
                <button type="submit"
                    class="w-11 h-11 bg-primary text-white rounded-xl flex items-center justify-center hover:scale-105 active:scale-95 transition-all shadow-md shadow-emerald-500/20">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    @yield('scripts')
</body>

</html>