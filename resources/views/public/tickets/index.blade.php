@extends('layouts.app')

@section('title', 'Vé Điện Tử - ' . $booking->booking_code)

@section('content')
<!-- Header -->
<header class="breadcrumb-header min-h-[30vh] flex flex-col justify-center text-center">
    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80" class="bg-image">
    <div class="overlay opacity-80" style="background: linear-gradient(135deg, #0f172a 0%, #10b981 100%);"></div>
    <div class="container relative z-10">
        <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter uppercase mb-2">
            Vé Điện Tử <span class="text-teal-200 italic">E-TICKET</span>
        </h1>
        <p class="text-teal-100 text-[10px] font-black uppercase tracking-[0.4em]">Mã đặt chỗ: {{ $booking->booking_code }}</p>
    </div>
</header>

<section class="py-16 bg-slate-50">
    <div class="container max-w-4xl">
        <div class="mb-8 flex justify-between items-center">
            <a href="{{ route('public.account.bookings.history') }}" class="btn btn-outline border-slate-200 text-slate-500 !px-6 !py-3 !text-[10px]">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
            <button onclick="window.print()" class="btn btn-primary !px-6 !py-3 !text-[10px] shadow-lg shadow-emerald-500/20">
                <i class="fas fa-print mr-2"></i> In tất cả vé
            </button>
        </div>

        <div class="space-y-8">
            @foreach($booking->passengers as $passenger)
            <!-- Ticket Card -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden flex flex-col md:flex-row relative print-ticket">
                <!-- Left: Info -->
                <div class="flex-1 p-8 md:p-10 border-b md:border-b-0 md:border-r border-dashed border-slate-300 relative">
                    <!-- Ticket Notch -->
                    <div class="hidden md:block absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-full border-l border-slate-300"></div>
                    
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <span class="px-3 py-1 bg-emerald-50 text-primary text-[9px] font-black uppercase tracking-widest rounded-lg border border-emerald-100 mb-3 inline-block">BOARDING PASS</span>
                            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">{{ $booking->departure->tour->title }}</h2>
                        </div>
                        <i class="fas fa-plane-departure text-4xl text-slate-100"></i>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Hành khách</p>
                            <p class="text-sm font-bold text-slate-900 uppercase">{{ $passenger->name }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Số CCCD / Hộ chiếu</p>
                            <p class="text-sm font-bold text-slate-900">{{ $passenger->id_card ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Ngày khởi hành</p>
                            <p class="text-sm font-bold text-primary">{{ date('d/m/Y', strtotime($booking->departure->start_date)) }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Giờ tập trung</p>
                            <p class="text-sm font-bold text-slate-900">Theo HDV thông báo</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Mã Vé</p>
                            <p class="text-lg font-mono font-black text-slate-800 tracking-widest">{{ $passenger->ticket_code }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Trạng thái</p>
                            @if($passenger->checked_in_at)
                                <span class="text-xs font-black text-blue-500 uppercase"><i class="fas fa-check-circle"></i> Đã Check-in</span>
                            @else
                                <span class="text-xs font-black text-emerald-500 uppercase"><i class="fas fa-ticket-alt"></i> Hợp lệ</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: QR Code -->
                <div class="w-full md:w-64 bg-slate-50 p-8 flex flex-col items-center justify-center relative">
                    <!-- Ticket Notch -->
                    <div class="hidden md:block absolute -left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-full border-r border-slate-300"></div>

                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest text-center mb-4">Quét mã để lên xe</p>
                    <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-200 mb-4">
                        @php
                            $verifyUrl = route('admin.tickets.verify', $passenger->ticket_code);
                            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($verifyUrl);
                        @endphp
                        <img src="{{ $qrUrl }}" alt="QR Code" class="w-32 h-32">
                    </div>
                    <p class="text-[8px] text-slate-500 font-bold uppercase tracking-widest text-center">Vui lòng xuất trình mã QR này cho hướng dẫn viên.</p>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-8 p-6 bg-amber-50 rounded-2xl border border-amber-100 flex items-start gap-4">
            <i class="fas fa-info-circle text-amber-500 text-lg mt-1"></i>
            <div>
                <h4 class="text-xs font-black text-amber-800 uppercase mb-2">Lưu ý quan trọng</h4>
                <ul class="text-[10px] text-amber-700 font-bold space-y-2 uppercase tracking-widest">
                    <li>- Quý khách vui lòng lưu giữ mã QR cẩn thận hoặc chụp màn hình lại.</li>
                    <li>- Trình mã QR cho hướng dẫn viên khi điểm danh lên xe/nhận phòng.</li>
                    <li>- Vui lòng có mặt tại điểm hẹn đúng giờ (trước 15-30 phút).</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<style>
    @media print {
        header, nav, .btn, footer {
            display: none !important;
        }
        body {
            background: #fff !important;
        }
        .print-ticket {
            page-break-inside: avoid;
            box-shadow: none !important;
            border: 2px solid #000 !important;
            margin-bottom: 2rem !important;
        }
    }
</style>
@endsection
