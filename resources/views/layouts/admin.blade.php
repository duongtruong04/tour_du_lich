<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - {{ $settings['site_name'] ?? 'Tour Du Lịch' }}</title>

    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind -->
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
    <style>
        .sidebar-active { 
            background: rgba(20, 184, 166, 0.1); 
            border-left: 4px solid var(--primary);
            color: var(--primary) !important;
        }
        .sidebar-link:hover {
            background: rgba(20, 184, 166, 0.05);
            color: var(--primary) !important;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-900 font-sans">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="if (window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }">
        <!-- Sidebar -->
        <aside class="bg-white border-r border-slate-200 transition-all duration-300 flex flex-col z-50 shadow-sm fixed inset-y-0 left-0 lg:sticky lg:top-0 lg:h-screen lg:flex" 
               :class="sidebarOpen ? 'translate-x-0 w-72' : '-translate-x-full lg:translate-x-0 lg:w-20 w-72'">
            <div class="h-20 flex items-center px-6 border-b border-slate-100 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/20">
                        <i class="fas fa-paper-plane text-white"></i>
                    </div>
                    <span class="text-xl font-black tracking-tighter uppercase text-slate-800 whitespace-nowrap" x-show="sidebarOpen" x-transition>{{ $settings['logo_text'] ?? 'ANTIGRAVITY' }}</span>
                </a>
            </div>
            
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-th-large w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Tổng quan</span>
                </a>

                <div class="pt-6 pb-2 px-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]" x-show="sidebarOpen">Sản phẩm & Điểm đến</div>
                
                <a href="{{ route('admin.tours.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.tours.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-route w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Quản lý Tour</span>
                </a>

                <a href="{{ route('admin.destinations.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.destinations.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-map-marker-alt w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Điểm đến</span>
                </a>

                <div class="pt-6 pb-2 px-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]" x-show="sidebarOpen">Kinh doanh & Khách hàng</div>

                <a href="{{ route('admin.bookings.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.bookings.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-ticket-alt w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Đơn hàng</span>
                </a>

                <a href="{{ route('admin.payments.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.payments.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-credit-card w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Thanh toán</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.users.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-users w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Người dùng</span>
                </a>

                <a href="{{ route('admin.promotions.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.promotions.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-percentage w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Khuyến mãi</span>
                </a>

                <div class="pt-6 pb-2 px-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]" x-show="sidebarOpen">Nội dung</div>

                <a href="{{ route('admin.news.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.news.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-newspaper w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Tin tức</span>
                </a>

                <a href="{{ route('admin.chats.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.chats.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-comments w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Hỗ trợ Chat</span>
                </a>

                <a href="{{ route('admin.reviews.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.reviews.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-star w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Đánh giá</span>
                </a>

                <div class="pt-6 pb-2 px-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]" x-show="sidebarOpen">Hệ thống</div>

                <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.settings.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-cog w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Cấu hình</span>
                </a>

                <a href="{{ route('admin.profile.index') }}" class="sidebar-link flex items-center gap-3 p-3 rounded-xl transition-all font-bold text-xs uppercase tracking-widest {{ request()->routeIs('admin.profile.*') ? 'sidebar-active' : 'text-slate-500' }}">
                    <i class="fas fa-user-shield w-5 text-center text-sm"></i>
                    <span x-show="sidebarOpen">Hồ sơ cá nhân</span>
                </a>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-slate-100 flex-shrink-0 bg-white">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl text-red-500 hover:bg-red-50 transition-all font-bold text-xs uppercase tracking-widest group">
                        <i class="fas fa-sign-out-alt w-5 text-center text-sm group-hover:translate-x-1 transition-transform"></i>
                        <span x-show="sidebarOpen">Đăng xuất</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div class="fixed inset-0 bg-dark/50 z-40 lg:hidden" x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition opacity-0" x-transition:enter-end="opacity-100" style="display: none;"></div>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 bg-slate-50">
            <!-- Header -->
            <header class="h-20 bg-white border-b border-slate-200 px-8 flex justify-between items-center sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:text-primary transition-all">
                        <i class="fas fa-bars" x-show="sidebarOpen"></i>
                        <i class="fas fa-indent" x-show="!sidebarOpen"></i>
                    </button>
                    <h1 class="text-sm font-black text-slate-800 uppercase tracking-widest">@yield('title', 'Admin Panel')</h1>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Notifications -->
                    <div class="relative group">
                        <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-primary transition-all">
                            <i class="far fa-bell text-lg"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                    </div>

                    <!-- User Profile -->
                    <div class="relative" x-data="{ openProfile: false }">
                        <button @click="openProfile = !openProfile" class="flex items-center gap-3 pl-6 border-l border-slate-200 text-left focus:outline-none group">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-black text-slate-800 tracking-tight leading-none mb-1 group-hover:text-primary transition-colors">{{ Auth::user()->full_name }}</p>
                                <p class="text-[9px] font-black text-primary uppercase tracking-widest leading-none">Administrator</p>
                            </div>
                            <img src="{{ Auth::user()->avatar_url }}" 
                                 class="w-10 h-10 rounded-xl object-cover shadow-lg shadow-teal-500/10 border-2 border-teal-50 group-hover:border-primary transition-colors">
                            <i class="fas fa-chevron-down text-xs text-slate-400 group-hover:text-primary transition-colors"></i>
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="openProfile" @click.away="openProfile = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 overflow-hidden z-50" style="display: none;">
                            <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary transition-all">
                                <i class="fas fa-user-shield text-slate-400"></i> Thông tin tài khoản
                            </a>
                            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary transition-all">
                                <i class="fas fa-external-link-alt text-slate-400"></i> Xem trang ngoài (Public)
                            </a>
                            <div class="border-t border-slate-50 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-xs font-bold text-red-500 hover:bg-red-50 transition-all text-left">
                                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-8">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-bold flex items-center shadow-sm" x-data="{ show: true }" x-show="show">
                        <i class="fas fa-check-circle mr-3 text-emerald-500"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                        <button @click="show = false" class="ml-auto opacity-50 hover:opacity-100"><i class="fas fa-times"></i></button>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-2xl font-bold flex items-center shadow-sm" x-data="{ show: true }" x-show="show">
                        <i class="fas fa-exclamation-triangle mr-3 text-amber-500"></i>
                        <span class="text-sm">{{ session('warning') }}</span>
                        <button @click="show = false" class="ml-auto opacity-50 hover:opacity-100"><i class="fas fa-times"></i></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl font-bold flex items-center shadow-sm" x-data="{ show: true }" x-show="show">
                        <i class="fas fa-times-circle mr-3 text-rose-500"></i>
                        <span class="text-sm">{{ session('error') }}</span>
                        <button @click="show = false" class="ml-auto opacity-50 hover:opacity-100"><i class="fas fa-times"></i></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl font-bold shadow-sm">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-circle mr-3 text-rose-500"></i>
                            <span class="text-sm">Vui lòng kiểm tra lại các lỗi sau:</span>
                        </div>
                        <ul class="list-disc pl-10 text-xs space-y-1 opacity-80">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    @yield('scripts')
</body>
</html>
