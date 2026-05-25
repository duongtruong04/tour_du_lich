@extends('layouts.app')

@section('title', 'Đặt tour của tôi')

@section('content')
<!-- Breadcrumb Header -->
<header class="breadcrumb-header min-h-[45vh] flex flex-col justify-center text-center">
    <img src="https://images.unsplash.com/photo-1503220317375-aaad61436b1b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" class="bg-image">
    <div class="overlay opacity-60"></div>
    <div class="container relative z-10">
        <nav class="breadcrumb-nav justify-center mb-12">
            <a href="{{ route('home') }}">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <span class="text-white">Lịch sử đặt tour</span>
        </nav>
        <h1 class="text-4xl md:text-7xl font-black text-white tracking-tighter uppercase mb-2 leading-none">
            Hành trình <span class="text-primary italic">của tôi</span>
        </h1>
        <p class="text-teal-100 text-[10px] font-black uppercase tracking-[0.4em] opacity-80 italic">Lưu giữ khoảnh khắc, kết nối tương lai</p>
    </div>
</header>

<!-- Success/Error alerts -->
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

            <!-- Sidebar -->
            @include('public.account.partials.sidebar')

            <!-- Content Area -->
            <div>
                <!-- Filter & Summary Row -->
                <div style="background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 2px 12px rgba(0,0,0,.05); padding: 20px 24px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #ede9fe, #ddd6fe); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-history" style="color: #7c3aed; font-size: 16px;"></i>
                        </div>
                        <div>
                            <p style="font-size: 16px; font-weight: 900; color: #1e293b; margin: 0;">Tất cả đơn đặt tour</p>
                            <p style="font-size: 12px; color: #94a3b8; margin: 0;">{{ $bookings->total() }} đơn hàng</p>
                        </div>
                    </div>
                    <a href="{{ route('public.tours.index') }}"
                       style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; padding: 10px 20px; border-radius: 12px; font-size: 12px; font-weight: 800; text-decoration: none; text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.transform='none'">
                        <i class="fas fa-plus"></i>Đặt tour mới
                    </a>
                </div>

                <!-- Filter tabs -->
                <div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
                    @php
                        $statuses = [
                            'all' => ['label' => 'Tất cả', 'color' => '#64748b', 'bg' => '#f1f5f9'],
                            'Pending' => ['label' => 'Chờ xử lý', 'color' => '#d97706', 'bg' => '#fffbeb'],
                            'Confirmed' => ['label' => 'Đã xác nhận', 'color' => '#059669', 'bg' => '#ecfdf5'],
                            'Completed' => ['label' => 'Hoàn thành', 'color' => '#475569', 'bg' => '#f1f5f9'],
                            'Cancelled' => ['label' => 'Đã hủy', 'color' => '#dc2626', 'bg' => '#fff1f2'],
                        ];
                        $currentFilter = request('status', 'all');
                    @endphp
                    @foreach($statuses as $key => $st)
                    <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}"
                       style="padding: 8px 16px; border-radius: 10px; font-size: 11px; font-weight: 800; text-decoration: none; text-transform: uppercase; letter-spacing: 0.08em; transition: all 0.2s;
                              {{ $currentFilter == $key ? 'background: '.$st['bg'].'; color: '.$st['color'].'; box-shadow: 0 2px 8px rgba(0,0,0,.08);' : 'background: #fff; color: #94a3b8; border: 1px solid #e2e8f0;' }}">
                        {{ $st['label'] }}
                    </a>
                    @endforeach
                </div>

                @if($bookings->isEmpty())
                <!-- Empty State -->
                <div style="background: #fff; border-radius: 24px; border: 2px dashed #e2e8f0; padding: 80px 40px; text-align: center;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-radius: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <i class="fas fa-map-marked-alt" style="font-size: 32px; color: #cbd5e1;"></i>
                    </div>
                    <h4 style="font-size: 20px; font-weight: 900; color: #475569; margin: 0 0 8px; text-transform: uppercase; letter-spacing: -0.02em;">Chưa có hành trình nào</h4>
                    <p style="font-size: 14px; color: #94a3b8; margin: 0 0 24px;">Bạn chưa đặt chuyến đi nào. Hãy khám phá các tour hấp dẫn!</p>
                    <a href="{{ route('public.tours.index') }}"
                       style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; padding: 14px 32px; border-radius: 16px; font-size: 13px; font-weight: 800; text-decoration: none; text-transform: uppercase; letter-spacing: 0.1em; box-shadow: 0 4px 15px rgba(16,185,129,.3);">
                        <i class="fas fa-search"></i>Khám phá tour ngay
                    </a>
                </div>

                @else
                <!-- Booking List -->
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($bookings as $booking)
                    @php
                        $statusMap = [
                            'Pending'   => ['label' => 'Chờ xác nhận', 'bg' => '#fffbeb', 'color' => '#d97706', 'icon' => 'fas fa-clock'],
                            'Confirmed' => ['label' => 'Đã xác nhận',  'bg' => '#ecfdf5', 'color' => '#059669', 'icon' => 'fas fa-check-circle'],
                            'Completed' => ['label' => 'Hoàn thành',   'bg' => '#f1f5f9', 'color' => '#475569', 'icon' => 'fas fa-flag-checkered'],
                            'Cancelled' => ['label' => 'Đã hủy',       'bg' => '#fff1f2', 'color' => '#dc2626', 'icon' => 'fas fa-times-circle'],
                        ];
                        $s = $statusMap[$booking->status] ?? ['label' => $booking->status, 'bg' => '#f1f5f9', 'color' => '#64748b', 'icon' => 'fas fa-circle'];
                        $tourImage = $booking->departure->tour->images->first();
                    @endphp
                    <div style="background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 2px 12px rgba(0,0,0,.05); overflow: hidden; transition: all 0.3s;"
                         onmouseover="this.style.boxShadow='0 8px 30px rgba(0,0,0,.1)'; this.style.transform='translateY(-2px)'"
                         onmouseout="this.style.boxShadow='0 2px 12px rgba(0,0,0,.05)'; this.style.transform='none'">
                        
                        <!-- Status bar top -->
                        <div style="height: 3px; background: {{ $s['color'] }};"></div>
                        
                        <div style="display: flex; gap: 0; flex-wrap: wrap;">
                            <!-- Tour Image -->
                            <div style="width: 220px; min-height: 100%; overflow: hidden; flex-shrink: 0; position: relative;">
                                <img src="{{ $tourImage ? $tourImage->image_url : 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=400&h=300&fit=crop' }}"
                                     style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;"
                                     onmouseover="this.style.transform='scale(1.05)'"
                                     onmouseout="this.style.transform='scale(1)'">
                            </div>
                            
                            <!-- Content -->
                            <div style="flex: 1; padding: 20px 24px; min-width: 0;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap;">
                                    <div style="min-width: 0;">
                                        <p style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.15em; margin: 0 0 4px;">
                                            Mã đơn: #{{ $booking->booking_code }}
                                        </p>
                                        <h4 style="font-size: 16px; font-weight: 900; color: #1e293b; margin: 0; line-height: 1.3; text-transform: uppercase; letter-spacing: -0.02em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                            {{ $booking->departure->tour->title }}
                                        </h4>
                                    </div>
                                    <!-- Status badge -->
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 10px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; flex-shrink: 0;
                                                 background: {{ $s['bg'] }}; color: {{ $s['color'] }};">
                                        <i class="{{ $s['icon'] }}"></i>{{ $s['label'] }}
                                    </span>
                                </div>
                                
                                <!-- Details Grid -->
                                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f8fafc;">
                                    <div>
                                        <p style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.12em; margin: 0 0 4px;">Khởi hành</p>
                                        <p style="font-size: 13px; font-weight: 700; color: #1e293b; margin: 0;">{{ date('d/m/Y', strtotime($booking->departure->start_date)) }}</p>
                                    </div>
                                    <div>
                                        <p style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.12em; margin: 0 0 4px;">Thời lượng</p>
                                        <p style="font-size: 13px; font-weight: 700; color: #1e293b; margin: 0;">{{ $booking->departure->tour->duration ?? '3 ngày 2 đêm' }}</p>
                                    </div>
                                    <div>
                                        <p style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.12em; margin: 0 0 4px;">Tổng tiền</p>
                                        <p style="font-size: 13px; font-weight: 900; color: #10b981; margin: 0;">{{ number_format($booking->total_price) }}đ</p>
                                    </div>
                                    <div>
                                        <p style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.12em; margin: 0 0 4px;">Thanh toán</p>
                                        @php
                                            $payColor = $booking->payment_status == 'Paid' ? '#059669' : ($booking->payment_status == 'Refunded' ? '#dc2626' : '#d97706');
                                            $payLabel = ['Unpaid' => 'Chưa thanh toán', 'Paid' => 'Đã thanh toán', 'Refunded' => 'Đã hoàn tiền'];
                                        @endphp
                                        <p style="font-size: 12px; font-weight: 700; color: {{ $payColor }}; margin: 0;">{{ $payLabel[$booking->payment_status] ?? $booking->payment_status }}</p>
                                    </div>
                                </div>

                                <!-- Additional info section (Expandable / Rich Data) -->
                                <div style="background: #f8fafc; border-radius: 16px; padding: 16px; margin-top: 16px; border: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 12px;">
                                    <!-- Row 1: Tour specs & transport -->
                                    <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 12px; color: #64748b; align-items: center;">
                                        <span style="display: inline-flex; align-items: center; gap: 6px;">
                                            <i class="fas fa-bus text-emerald-500"></i> Phương tiện: <strong style="color: #1e293b;">{{ $booking->departure->tour->transportation ?? 'Xe du lịch chất lượng cao' }}</strong>
                                        </span>
                                        <span style="display: inline-flex; align-items: center; gap: 6px;">
                                            <i class="fas fa-map-marker-alt text-amber-500"></i> Hành trình: <strong style="color: #1e293b;">{{ $booking->departure->tour->destinations->pluck('name')->implode(', ') ?: 'Hành trình trọn gói' }}</strong>
                                        </span>
                                        <span style="display: inline-flex; align-items: center; gap: 6px;">
                                            <i class="fas fa-chair text-blue-500"></i> Chỗ trống chuyến: <strong style="color: #1e293b;">{{ $booking->departure->available_seats }} chỗ</strong>
                                        </span>
                                    </div>

                                    <div style="height: 1px; background: #e2e8f0;"></div>

                                    <!-- Row 2: Passengers & Notes -->
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 12px;">
                                        <div>
                                            <p style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin: 0 0 4px;">Danh sách hành khách ({{ $booking->passengers->count() }})</p>
                                            <p style="color: #334155; margin: 0; font-weight: 600; line-height: 1.4;">
                                                {{ $booking->passengers->pluck('name')->implode(', ') ?: 'Chưa cập nhật tên hành khách' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin: 0 0 4px;">Ghi chú của bạn</p>
                                            <p style="color: #334155; margin: 0; font-style: italic; line-height: 1.4;">
                                                "{{ $booking->notes ?? 'Không có ghi chú thêm' }}"
                                            </p>
                                        </div>
                                    </div>

                                    <div style="height: 1px; background: #e2e8f0;"></div>

                                    <!-- Row 3: Status guide -->
                                    <div style="display: flex; align-items: center; gap: 8px; font-size: 11px; color: #475569;">
                                        <i class="fas fa-info-circle text-blue-500 flex-shrink-0"></i>
                                        <span>
                                            @if($booking->status == 'Pending')
                                                <strong>Hướng dẫn:</strong> Đang chờ điều hành tour kiểm kiểm tra chỗ và xác nhận. Vui lòng giữ liên lạc qua số điện thoại.
                                            @elseif($booking->status == 'Confirmed')
                                                <strong>Hướng dẫn:</strong> Đã xác nhận chỗ. Hướng dẫn viên sẽ liên hệ trước ngày khởi hành 1-2 ngày qua Zalo/SĐT.
                                            @elseif($booking->status == 'Completed')
                                                <strong>Hướng dẫn:</strong> Chuyến đi đã kết thúc. Cảm ơn quý khách đã đồng hành cùng {{ $settings['site_name'] ?? 'Tour Travel' }}!
                                            @elseif($booking->status == 'Cancelled')
                                                <strong>Hướng dẫn:</strong> Đơn đặt tour đã bị hủy. Nếu có thắc mắc vui lòng liên hệ hotline 24/7.
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Action buttons -->
                                <div style="display: flex; align-items: center; gap: 10px; margin-top: 16px;">
                                    <a href="{{ route('public.bookings.success', $booking->booking_code) }}"
                                       style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 11px; font-weight: 700; color: #475569; text-decoration: none; text-transform: uppercase; letter-spacing: 0.08em; transition: all 0.2s;"
                                       onmouseover="this.style.borderColor='#10b981'; this.style.color='#10b981'; this.style.background='#f0fdf4'"
                                       onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#475569'; this.style.background='#f8fafc'">
                                        <i class="fas fa-eye"></i>Xem chi tiết
                                    </a>
                                    <a href="{{ route('public.tours.show', $booking->departure->tour->slug) }}"
                                       style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 11px; font-weight: 700; color: #475569; text-decoration: none; text-transform: uppercase; letter-spacing: 0.08em; transition: all 0.2s;"
                                       onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6'; this.style.background='#eff6ff'"
                                       onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#475569'; this.style.background='#f8fafc'">
                                        <i class="fas fa-route"></i>Xem tour
                                    </a>
                                    @if($booking->payment_status == 'Paid')
                                    <a href="{{ route('public.tickets.show', $booking->id) }}"
                                       style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #ecfdf5; border: 1px solid #10b981; border-radius: 10px; font-size: 11px; font-weight: 800; color: #059669; text-decoration: none; text-transform: uppercase; letter-spacing: 0.08em; transition: all 0.2s; box-shadow: 0 4px 10px rgba(16,185,129,0.15);"
                                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(16,185,129,0.25)'"
                                       onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 10px rgba(16,185,129,0.15)'">
                                        <i class="fas fa-qrcode"></i>Xem vé điện tử
                                    </a>
                                    @endif
                                    @if($booking->status == 'Pending')
                                    <span style="margin-left: auto; display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: #d97706;">
                                        <i class="fas fa-clock"></i>Chờ nhân viên xác nhận
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                <div style="margin-top: 24px; display: flex; justify-content: center;">
                    {{ $bookings->links() }}
                </div>
                @endif
                @endif

            </div>
        </div>
    </div>
</section>

<style>
    @media (max-width: 1024px) {
        .container > div[style*="grid-template-columns"] { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 640px) {
        div[style*="grid-template-columns: repeat(4, 1fr)"] { grid-template-columns: 1fr 1fr !important; }
        div[style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
        div[style*="width: 220px"] { width: 100% !important; height: 200px !important; }
    }
</style>
@endsection
