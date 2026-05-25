@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<!-- Breadcrumb Header -->
<header class="breadcrumb-header min-h-[45vh] flex flex-col justify-center">
    <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" class="bg-image">
    <div class="overlay opacity-60"></div>
    <div class="container relative z-10 text-center">
        <nav class="breadcrumb-nav justify-center mb-12">
            <a href="{{ route('home') }}">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <span class="text-white">Tài khoản của tôi</span>
        </nav>
        <h1 class="text-4xl md:text-7xl font-black text-white tracking-tighter uppercase mb-2 leading-none">
            Hồ sơ <span class="text-primary italic">cá nhân</span>
        </h1>
        <p class="text-teal-100 text-[10px] font-black uppercase tracking-[0.4em] opacity-80">Thông tin tài khoản {{ $settings['site_name'] ?? 'Tour Travel' }}</p>
    </div>
</header>

@if(session('success'))
<div class="container" style="margin-top: 24px;">
    <div style="background: #ecfdf5; border: 1px solid #6ee7b7; color: #065f46; padding: 14px 20px; border-radius: 16px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle" style="color: #10b981; font-size: 18px;"></i>
        {{ session('success') }}
    </div>
</div>
@endif

<!-- Main Content -->
<section class="py-16 bg-slate-50">
    <div class="container">
        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px; align-items: start;">

            <!-- ============ LEFT SIDEBAR ============ -->
            @include('public.account.partials.sidebar')

            <!-- ============ RIGHT CONTENT ============ -->
            <div style="display: flex; flex-direction: column; gap: 24px;">

                <!-- Stats Cards -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                    <!-- Stat 1 -->
                    <div style="background: #fff; border-radius: 20px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 2px 12px rgba(0,0,0,.05); display: flex; align-items: center; gap: 16px;">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #ecfdf5, #d1fae5); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-suitcase-rolling" style="color: #10b981; font-size: 18px;"></i>
                        </div>
                        <div>
                            <p style="font-size: 24px; font-weight: 900; color: #1e293b; line-height: 1;">{{ $user->bookings->count() }}</p>
                            <p style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Chuyến đi</p>
                        </div>
                    </div>
                    <!-- Stat 2 -->
                    <div style="background: #fff; border-radius: 20px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 2px 12px rgba(0,0,0,.05); display: flex; align-items: center; gap: 16px;">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #fffbeb, #fef3c7); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
                        </div>
                        <div>
                            <p style="font-size: 24px; font-weight: 900; color: #1e293b; line-height: 1;">{{ $user->reviews->count() }}</p>
                            <p style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Đánh giá</p>
                        </div>
                    </div>
                    <!-- Stat 3 -->
                    <div style="background: #fff; border-radius: 20px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 2px 12px rgba(0,0,0,.05); display: flex; align-items: center; gap: 16px;">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #eff6ff, #dbeafe); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-wallet" style="color: #3b82f6; font-size: 18px;"></i>
                        </div>
                        <div>
                            <p style="font-size: 18px; font-weight: 900; color: #1e293b; line-height: 1;">{{ number_format($user->bookings->where('status', 'Completed')->sum('total_price') / 1000000, 1) }}M</p>
                            <p style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Đã chi tiêu</p>
                        </div>
                    </div>
                </div>

                <!-- Thống kê trạng thái đơn hàng chi tiết -->
                <div style="background: #fff; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,.06); overflow: hidden; padding: 32px;">
                    <h3 style="font-size: 18px; font-weight: 900; color: #1e293b; text-transform: uppercase; letter-spacing: -0.03em; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-chart-pie" style="color: #8b5cf6; font-size: 16px;"></i>
                        Thống kê trạng thái đơn hàng
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                        @php
                            $pendingCount = $user->bookings->where('status', 'Pending')->count();
                            $confirmedCount = $user->bookings->where('status', 'Confirmed')->count();
                            $completedCount = $user->bookings->where('status', 'Completed')->count();
                            $cancelledCount = $user->bookings->where('status', 'Cancelled')->count();
                        @endphp
                        
                        <div style="background: #fffbeb; border: 1px solid #fef3c7; padding: 20px; border-radius: 20px; text-align: center;">
                            <div style="width: 40px; height: 40px; background: #f59e0b; color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 16px;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <p style="font-size: 22px; font-weight: 900; color: #b45309; margin: 0 0 4px;">{{ $pendingCount }}</p>
                            <p style="font-size: 11px; font-weight: 800; color: #d97706; text-transform: uppercase; margin: 0;">Chờ xử lý</p>
                        </div>

                        <div style="background: #eff6ff; border: 1px solid #dbeafe; padding: 20px; border-radius: 20px; text-align: center;">
                            <div style="width: 40px; height: 40px; background: #3b82f6; color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 16px;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p style="font-size: 22px; font-weight: 900; color: #1d4ed8; margin: 0 0 4px;">{{ $confirmedCount }}</p>
                            <p style="font-size: 11px; font-weight: 800; color: #2563eb; text-transform: uppercase; margin: 0;">Đã xác nhận</p>
                        </div>

                        <div style="background: #ecfdf5; border: 1px solid #d1fae5; padding: 20px; border-radius: 20px; text-align: center;">
                            <div style="width: 40px; height: 40px; background: #10b981; color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 16px;">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <p style="font-size: 22px; font-weight: 900; color: #047857; margin: 0 0 4px;">{{ $completedCount }}</p>
                            <p style="font-size: 11px; font-weight: 800; color: #059669; text-transform: uppercase; margin: 0;">Hoàn thành</p>
                        </div>

                        <div style="background: #fff1f2; border: 1px solid #ffe4e6; padding: 20px; border-radius: 20px; text-align: center;">
                            <div style="width: 40px; height: 40px; background: #f43f5e; color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 16px;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <p style="font-size: 22px; font-weight: 900; color: #be123c; margin: 0 0 4px;">{{ $cancelledCount }}</p>
                            <p style="font-size: 11px; font-weight: 800; color: #e11d48; text-transform: uppercase; margin: 0;">Đã hủy</p>
                        </div>
                    </div>
                </div>

                <!-- Quyền lợi hạng thành viên -->
                <div style="background: #fff; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,.06); overflow: hidden; padding: 32px;">
                    <h3 style="font-size: 18px; font-weight: 900; color: #1e293b; text-transform: uppercase; letter-spacing: -0.03em; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-gift" style="color: #10b981; font-size: 16px;"></i>
                        Quyền lợi thành viên của bạn
                    </h3>
                    
                    @php
                        $completedTrips = $user->bookings->where('status', 'Completed')->count();
                    @endphp
                    
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                        <div style="padding: 24px; border: 2px solid {{ $completedTrips >= 5 ? '#fde68a' : '#e2e8f0' }}; background: {{ $completedTrips >= 5 ? '#fffbeb' : '#f8fafc' }}; border-radius: 20px; position: relative;">
                            @if($completedTrips >= 5)
                            <span style="position: absolute; top: -12px; right: 20px; background: #f59e0b; color: #fff; font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 999px; text-transform: uppercase;">Hạng của bạn</span>
                            @endif
                            <h4 style="font-size: 15px; font-weight: 900; color: {{ $completedTrips >= 5 ? '#92400e' : '#64748b' }}; margin: 0 0 8px; text-transform: uppercase;">Thành viên Vàng</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 16px;">Từ 5 chuyến đi hoàn thành</p>
                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 12px; color: #475569;">
                                <li style="margin-bottom: 8px;"><i class="fas fa-check text-emerald-500 mr-2"></i>Giảm 5% trực tiếp khi đặt tour</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-check text-emerald-500 mr-2"></i>Hỗ trợ ưu tiên 24/7</li>
                                <li><i class="fas fa-check text-emerald-500 mr-2"></i>Quà tặng độc quyền sinh nhật</li>
                            </ul>
                        </div>

                        <div style="padding: 24px; border: 2px solid {{ ($completedTrips >= 1 && $completedTrips < 5) ? '#bae6fd' : '#e2e8f0' }}; background: {{ ($completedTrips >= 1 && $completedTrips < 5) ? '#f0f9ff' : '#f8fafc' }}; border-radius: 20px; position: relative;">
                            @if($completedTrips >= 1 && $completedTrips < 5)
                            <span style="position: absolute; top: -12px; right: 20px; background: #0ea5e9; color: #fff; font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 999px; text-transform: uppercase;">Hạng của bạn</span>
                            @endif
                            <h4 style="font-size: 15px; font-weight: 900; color: {{ ($completedTrips >= 1 && $completedTrips < 5) ? '#075985' : '#64748b' }}; margin: 0 0 8px; text-transform: uppercase;">Thành viên Bạc</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 16px;">Từ 1 chuyến đi hoàn thành</p>
                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 12px; color: #475569;">
                                <li style="margin-bottom: 8px;"><i class="fas fa-check text-emerald-500 mr-2"></i>Giảm 2% trực tiếp khi đặt tour</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-check text-emerald-500 mr-2"></i>Hỗ trợ tiêu chuẩn 24/7</li>
                                <li><i class="fas fa-check text-emerald-500 mr-2"></i>Nhận bản tin ưu đãi sớm</li>
                            </ul>
                        </div>

                        <div style="padding: 24px; border: 2px solid {{ $completedTrips == 0 ? '#bbf7d0' : '#e2e8f0' }}; background: {{ $completedTrips == 0 ? '#f0fdf4' : '#f8fafc' }}; border-radius: 20px; position: relative;">
                            @if($completedTrips == 0)
                            <span style="position: absolute; top: -12px; right: 20px; background: #10b981; color: #fff; font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 999px; text-transform: uppercase;">Hạng của bạn</span>
                            @endif
                            <h4 style="font-size: 15px; font-weight: 900; color: {{ $completedTrips == 0 ? '#065f46' : '#64748b' }}; margin: 0 0 8px; text-transform: uppercase;">Thành viên mới</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 16px;">Đăng ký tài khoản</p>
                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 12px; color: #475569;">
                                <li style="margin-bottom: 8px;"><i class="fas fa-check text-emerald-500 mr-2"></i>Tích lũy chuyến đi thăng hạng</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-check text-emerald-500 mr-2"></i>Hỗ trợ tư vấn 24/7</li>
                                <li><i class="fas fa-check text-emerald-500 mr-2"></i>Truy cập cẩm nang du lịch</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Đánh giá gần đây của bạn -->
                <div style="background: #fff; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,.06); overflow: hidden; padding: 32px;">
                    <h3 style="font-size: 18px; font-weight: 900; color: #1e293b; text-transform: uppercase; letter-spacing: -0.03em; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-star" style="color: #f59e0b; font-size: 16px;"></i>
                        Đánh giá tour gần đây của bạn
                    </h3>
                    
                    @if($user->reviews->isEmpty())
                        <div style="padding: 40px 20px; text-align: center; background: #f8fafc; border-radius: 20px; border: 2px dashed #e2e8f0;">
                            <i class="far fa-comment-dots text-4xl text-slate-300 mb-4 block"></i>
                            <p style="font-size: 13px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 8px;">Bạn chưa viết đánh giá nào</p>
                            <p style="font-size: 12px; color: #94a3b8; margin: 0 0 20px;">Hãy trải nghiệm các chuyến đi và chia sẻ cảm nhận với cộng đồng {{ $settings['site_name'] ?? 'Tour Travel' }} nhé!</p>
                            <a href="{{ route('public.account.bookings.history') }}" style="display: inline-flex; align-items: center; gap: 8px; background: #10b981; color: #fff; padding: 10px 24px; border-radius: 12px; font-size: 12px; font-weight: 800; text-decoration: none; text-transform: uppercase;">
                                <i class="fas fa-history"></i> Xem chuyến đi đã hoàn thành
                            </a>
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($user->reviews()->latest()->take(3)->get() as $review)
                            <div style="padding: 20px 24px; background: #f8fafc; border-radius: 20px; border: 1px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.background='#fff'; this.style.boxShadow='0 4px 15px rgba(0,0,0,.05)'" onmouseout="this.style.background='#f8fafc'; this.style.boxShadow='none'">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                                    <div>
                                        <h4 style="font-size: 15px; font-weight: 900; color: #1e293b; margin: 0 0 4px; text-transform: uppercase;">{{ $review->tour->title ?? 'Tour du lịch' }}</h4>
                                        <p style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">Đã đánh giá ngày: {{ $review->created_at->format('d/m/Y') }}</p>
                                    </div>
                                    <div style="display: flex; gap: 2px; color: #f59e0b; font-size: 12px;">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p style="font-size: 13px; color: #475569; font-style: italic; line-height: 1.6; margin: 0; padding-left: 12px; border-left: 3px solid #10b981;">"{{ $review->comment }}"</p>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Profile Info Form -->
                <div style="background: #fff; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,.06); overflow: hidden;">
                    <div style="height: 4px; background: linear-gradient(90deg, #10b981, #0d9488, #06b6d4);"></div>
                    <div style="padding: 32px;">
                        <h3 style="font-size: 18px; font-weight: 900; color: #1e293b; text-transform: uppercase; letter-spacing: -0.03em; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-user-edit" style="color: #10b981; font-size: 16px;"></i>
                            Thông tin cơ bản
                        </h3>

                        @if($errors->has('full_name') || $errors->has('phone'))
                        <div style="background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Vui lòng kiểm tra lại thông tin đã nhập.
                        </div>
                        @endif

                        <form action="{{ route('public.account.update_profile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="avatar-upload" name="avatar" class="hidden" onchange="this.form.submit()">

                            <!-- Avatar Upload Row -->
                            <div style="display: flex; align-items: center; gap: 20px; padding: 20px; background: #f8fafc; border-radius: 16px; margin-bottom: 24px;">
                                <div style="position: relative;">
                                    <img src="{{ $user->avatar_url }}"
                                         style="width: 80px; height: 80px; border-radius: 20px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,.12);">
                                    <label for="avatar-upload"
                                           style="position: absolute; bottom: -6px; right: -6px; width: 28px; height: 28px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(16,185,129,.3);">
                                        <i class="fas fa-camera" style="color: #fff; font-size: 11px;"></i>
                                    </label>
                                </div>
                                <div>
                                    <p style="font-size: 13px; font-weight: 800; color: #1e293b;">Ảnh đại diện</p>
                                    <p style="font-size: 11px; color: #94a3b8; margin-top: 2px;">Nhấn vào biểu tượng máy ảnh để thay đổi</p>
                                    <p style="font-size: 10px; color: #cbd5e1; margin-top: 4px;">JPG, PNG, GIF, WebP. Tối đa 2MB.</p>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <!-- Full Name -->
                                <div>
                                    <label style="display: block; font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">
                                        Họ và tên <span style="color: #f43f5e;">*</span>
                                    </label>
                                    <div style="position: relative;">
                                        <i class="fas fa-user" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                                        <input type="text" name="full_name"
                                               value="{{ old('full_name', $user->full_name) }}"
                                               style="width: 100%; padding: 12px 12px 12px 44px; background: #f8fafc; border: 2px solid {{ $errors->has('full_name') ? '#f43f5e' : '#e2e8f0' }}; border-radius: 12px; font-size: 14px; font-weight: 600; color: #1e293b; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                                               onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='{{ $errors->has('full_name') ? '#f43f5e' : '#e2e8f0' }}'">
                                    </div>
                                    @error('full_name')
                                        <p style="font-size: 11px; color: #f43f5e; font-weight: 700; margin-top: 6px;">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label style="display: block; font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">
                                        Số điện thoại
                                    </label>
                                    <div style="position: relative;">
                                        <i class="fas fa-phone" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                                        <input type="tel" name="phone"
                                               value="{{ old('phone', $user->phone) }}"
                                               style="width: 100%; padding: 12px 12px 12px 44px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600; color: #1e293b; outline: none; box-sizing: border-box;"
                                               onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                                    </div>
                                </div>

                                <!-- Email (disabled) -->
                                <div>
                                    <label style="display: block; font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">
                                        Email (không thể thay đổi)
                                    </label>
                                    <div style="position: relative;">
                                        <i class="fas fa-envelope" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #cbd5e1; font-size: 13px;"></i>
                                        <input type="email" value="{{ $user->email }}" disabled
                                               style="width: 100%; padding: 12px 12px 12px 44px; background: #f1f5f9; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600; color: #94a3b8; box-sizing: border-box; cursor: not-allowed;">
                                    </div>
                                </div>

                                <!-- Member since -->
                                <div>
                                    <label style="display: block; font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">
                                        Thành viên từ
                                    </label>
                                    <div style="position: relative;">
                                        <i class="fas fa-calendar-check" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #cbd5e1; font-size: 13px;"></i>
                                        <input type="text" value="{{ $user->created_at->format('d/m/Y') }}" disabled
                                               style="width: 100%; padding: 12px 12px 12px 44px; background: #f1f5f9; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600; color: #94a3b8; box-sizing: border-box; cursor: not-allowed;">
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                                <button type="submit"
                                        style="background: linear-gradient(135deg, #10b981, #059669); color: #fff; padding: 12px 32px; border-radius: 14px; font-weight: 800; font-size: 13px; letter-spacing: 0.1em; text-transform: uppercase; border: none; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(16,185,129,.3);"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(16,185,129,.4)'"
                                        onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 15px rgba(16,185,129,.3)'">
                                    <i class="fas fa-save mr-2"></i>Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div style="background: #fff; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,.06); overflow: hidden;">
                    <div style="padding: 32px;">
                        <h3 style="font-size: 18px; font-weight: 900; color: #1e293b; text-transform: uppercase; letter-spacing: -0.03em; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-shield-alt" style="color: #f59e0b; font-size: 16px;"></i>
                            Đổi mật khẩu
                        </h3>

                        @if($errors->has('current_password') || $errors->has('password'))
                        <div style="background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                            <i class="fas fa-exclamation-triangle mr-2"></i>{{ $errors->first('current_password') ?: $errors->first('password') }}
                        </div>
                        @endif

                        <form action="{{ route('public.account.update_password') }}" method="POST">
                            @csrf
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div style="grid-column: 1 / -1;">
                                    <label style="display: block; font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">Mật khẩu hiện tại</label>
                                    <div style="position: relative;">
                                        <i class="fas fa-lock" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                                        <input type="password" name="current_password" placeholder="••••••••"
                                               style="width: 100%; padding: 12px 12px 12px 44px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600; color: #1e293b; outline: none; box-sizing: border-box;"
                                               onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                                    </div>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">Mật khẩu mới</label>
                                    <div style="position: relative;">
                                        <i class="fas fa-lock" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                                        <input type="password" name="password" placeholder="Tối thiểu 8 ký tự"
                                               style="width: 100%; padding: 12px 12px 12px 44px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600; color: #1e293b; outline: none; box-sizing: border-box;"
                                               onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                                    </div>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">Xác nhận mật khẩu mới</label>
                                    <div style="position: relative;">
                                        <i class="fas fa-lock" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                                        <input type="password" name="password_confirmation" placeholder="••••••••"
                                               style="width: 100%; padding: 12px 12px 12px 44px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600; color: #1e293b; outline: none; box-sizing: border-box;"
                                               onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                                <button type="submit"
                                        style="background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; padding: 12px 32px; border-radius: 14px; font-weight: 800; font-size: 13px; letter-spacing: 0.1em; text-transform: uppercase; border: none; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(245,158,11,.3);"
                                        onmouseover="this.style.transform='translateY(-2px)'"
                                        onmouseout="this.style.transform='none'">
                                    <i class="fas fa-key mr-2"></i>Cập nhật mật khẩu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div><!-- end right content -->
        </div>
    </div>
</section>

<style>
    @media (max-width: 1024px) {
        .container > div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
    }
    @media (max-width: 640px) {
        .container > div > div:last-child > div[style*="grid-template-columns: repeat(3"] {
            grid-template-columns: 1fr !important;
        }
        .container > div > div:last-child > div[style*="grid-template-columns: repeat(4"] {
            grid-template-columns: 1fr 1fr !important;
        }
        .container > div > div:last-child form > div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
