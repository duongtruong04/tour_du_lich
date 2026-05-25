@php
    $currentUser = Auth::user();
    $completedTrips = $currentUser->bookings->where('status', 'Completed')->count();
    $totalBookings = $currentUser->bookings->count();
    $totalSpent = $currentUser->bookings->where('status', 'Completed')->sum('total_price');
    
    if ($completedTrips >= 5) {
        $tierName = 'Thành viên Vàng';
        $tierBg = 'linear-gradient(135deg, #fffbeb, #fef3c7)';
        $tierBorder = '#fde68a';
        $tierColor = '#92400e';
        $tierIcon = 'fas fa-crown';
        $iconColor = '#f59e0b';
        $nextTier = 'Cấp bậc cao nhất';
        $progress = 100;
    } elseif ($completedTrips >= 1) {
        $tierName = 'Thành viên Bạc';
        $tierBg = 'linear-gradient(135deg, #f0f9ff, #e0f2fe)';
        $tierBorder = '#bae6fd';
        $tierColor = '#075985';
        $tierIcon = 'fas fa-medal';
        $iconColor = '#0ea5e9';
        $nextTier = 'Lên Vàng cần ' . (5 - $completedTrips) . ' chuyến';
        $progress = ($completedTrips / 5) * 100;
    } else {
        $tierName = 'Thành viên mới';
        $tierBg = 'linear-gradient(135deg, #f0fdf4, #dcfce7)';
        $tierBorder = '#bbf7d0';
        $tierColor = '#065f46';
        $tierIcon = 'fas fa-seedling';
        $iconColor = '#10b981';
        $nextTier = 'Lên Bạc cần 1 chuyến';
        $progress = 15;
    }
@endphp

<div style="background: #fff; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,.06); padding: 28px;">

    <!-- User Avatar & Name -->
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="position: relative; display: inline-block; margin-bottom: 12px;">
            <img src="{{ $currentUser->avatar_url }}"
                 style="width: 96px; height: 96px; border-radius: 22px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 8px 25px rgba(0,0,0,.12);">
            <!-- Online indicator -->
            <div style="position: absolute; bottom: 4px; right: 4px; width: 14px; height: 14px; background: #10b981; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 0 2px rgba(16,185,129,.2);"></div>
        </div>
        <h3 style="font-size: 18px; font-weight: 900; color: #1e293b; margin: 0 0 4px;">{{ $currentUser->full_name }}</h3>
        <p style="font-size: 10px; font-weight: 700; color: #10b981; text-transform: uppercase; letter-spacing: 0.15em; margin: 0;">Thành viên đồng hành</p>
        
        <div style="display: flex; flex-direction: column; gap: 4px; margin-top: 12px; background: #f8fafc; padding: 10px; border-radius: 14px; border: 1px solid #f1f5f9;">
            @if($currentUser->phone)
            <p style="font-size: 12px; color: #64748b; margin: 0; display: flex; align-items: center; justify-content: center; gap: 6px;">
                <i class="fas fa-phone text-[10px] text-emerald-500"></i> {{ $currentUser->phone }}
            </p>
            @endif
            <p style="font-size: 11px; color: #94a3b8; margin: 0; display: flex; align-items: center; justify-content: center; gap: 6px; overflow: hidden; text-overflow: ellipsis;">
                <i class="fas fa-envelope text-[10px] text-slate-400"></i> {{ $currentUser->email }}
            </p>
        </div>
    </div>

    <!-- Member badge & Progress -->
    <div style="background: {{ $tierBg }}; border-radius: 18px; padding: 16px; margin-bottom: 24px; border: 1px solid {{ $tierBorder }}; box-shadow: 0 4px 12px rgba(0,0,0,.03);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; border-radius: 10px; background: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,.05);">
                    <i class="{{ $tierIcon }}" style="color: {{ $iconColor }}; font-size: 14px;"></i>
                </div>
                <div>
                    <p style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">Hạng thành viên</p>
                    <h4 style="font-size: 13px; font-weight: 900; color: {{ $tierColor }}; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">{{ $tierName }}</h4>
                </div>
            </div>
            <span style="font-size: 12px; font-weight: 900; color: {{ $tierColor }}; padding: 2px 8px; border-radius: 8px; border: 1px solid {{ $tierBorder }}; background: #fff;">
                {{ $completedTrips }} tour
            </span>
        </div>

        <!-- Progress Bar -->
        <div style="space-y-1">
            <div style="display: flex; justify-content: space-between; font-size: 10px; font-weight: 700; color: {{ $tierColor }}; margin-bottom: 4px;">
                <span>Tiến trình thăng hạng</span>
                <span>{{ $nextTier }}</span>
            </div>
            <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.6); border-radius: 999px; overflow: hidden; border: 1px solid {{ $tierBorder }};">
                <div style="width: {{ $progress }}%; height: 100%; background: {{ $iconColor }}; border-radius: 999px; transition: width 1s;"></div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Summary in Sidebar -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 24px;">
        <div style="background: #f8fafc; padding: 12px; border-radius: 16px; border: 1px solid #f1f5f9; text-align: center;">
            <p style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 2px;">Tổng đơn</p>
            <p style="font-size: 16px; font-weight: 900; color: #1e293b; margin: 0;">{{ $totalBookings }}</p>
        </div>
        <div style="background: #f8fafc; padding: 12px; border-radius: 16px; border: 1px solid #f1f5f9; text-align: center;">
            <p style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 2px;">Chi tiêu</p>
            <p style="font-size: 14px; font-weight: 900; color: #10b981; margin: 0;">{{ number_format($totalSpent / 1000000, 1) }}M</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav style="display: flex; flex-direction: column; gap: 4px;">
        @php
            $navItems = [
                ['route' => 'public.account.profile', 'icon' => 'fas fa-user-circle', 'label' => 'Hồ sơ cá nhân', 'color' => '#10b981'],
                ['route' => 'public.account.bookings.history', 'icon' => 'fas fa-history', 'label' => 'Đặt tour của tôi', 'color' => '#3b82f6'],
                ['route' => 'public.tours.index', 'icon' => 'fas fa-search', 'label' => 'Tìm kiếm tour', 'color' => '#8b5cf6'],
            ];
        @endphp

        @foreach($navItems as $item)
        @php $isActive = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}"
           style="display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 14px; text-decoration: none; font-size: 12px; font-weight: 700; transition: all 0.2s;
                  {{ $isActive ? 'background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #065f46; box-shadow: 0 2px 8px rgba(16,185,129,.1);' : 'color: #64748b;' }}"
           onmouseover="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.background='#f8fafc'; this.style.color='#1e293b'; }"
           onmouseout="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.background='none'; this.style.color='#64748b'; }">
            <div style="width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
                        {{ $isActive ? 'background: #10b981;' : 'background: #f1f5f9;' }}">
                <i class="{{ $item['icon'] }}" style="font-size: 14px; {{ $isActive ? 'color: #fff;' : 'color: #94a3b8;' }}"></i>
            </div>
            <span style="text-transform: uppercase; letter-spacing: 0.08em;">{{ $item['label'] }}</span>
            @if($isActive)
            <i class="fas fa-chevron-right" style="margin-left: auto; font-size: 10px; color: #10b981;"></i>
            @endif
        </a>
        @endforeach
    </nav>

    <!-- Divider -->
    <div style="height: 1px; background: #f1f5f9; margin: 16px 0;"></div>

    <!-- Logout -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
                style="width: 100%; display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 14px; border: none; background: none; cursor: pointer; font-size: 12px; font-weight: 700; color: #ef4444; text-transform: uppercase; letter-spacing: 0.08em; transition: all 0.2s;"
                onmouseover="this.style.background='#fff1f2'"
                onmouseout="this.style.background='none'">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: #fff1f2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-sign-out-alt" style="font-size: 14px; color: #ef4444;"></i>
            </div>
            Đăng xuất
        </button>
    </form>

    <!-- Help link -->
    <div style="margin-top: 16px; padding: 16px; background: #f8fafc; border-radius: 16px; text-align: center; border: 1px solid #f1f5f9;">
        <p style="font-size: 11px; font-weight: 700; color: #64748b; margin: 0 0 8px;">Cần hỗ trợ khẩn cấp?</p>
        <a href="tel:{{ str_replace(' ', '', $settings['contact_phone'] ?? '0123456789') }}"
           style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 900; color: #10b981; text-decoration: none; text-transform: uppercase; letter-spacing: 0.1em; background: #ecfdf5; padding: 8px 16px; border-radius: 12px; border: 1px solid #a7f3d0;">
            <i class="fas fa-phone-alt"></i> {{ $settings['contact_phone'] ?? '0123 456 789' }}
        </a>
        <p style="font-size: 9px; color: #94a3b8; margin: 8px 0 0;">Hotline hoạt động 24/7 miễn phí</p>
    </div>
</div>
