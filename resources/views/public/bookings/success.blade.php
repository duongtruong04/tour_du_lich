@extends('layouts.app')

@section('title', 'Đặt tour thành công')

@section('content')
<!-- Breadcrumb Header -->
<header class="breadcrumb-header min-h-[45vh] flex flex-col justify-center text-center">
    <img src="https://images.unsplash.com/photo-1506461883276-594a12b11cf3?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" class="bg-image">
    <div class="overlay opacity-60"></div>
    <div class="container relative z-10">
        <nav class="breadcrumb-nav justify-center mb-12">
            <a href="{{ route('home') }}">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <span class="text-white">Đặt tour thành công</span>
        </nav>
        <h1 class="text-4xl md:text-7xl font-black text-white tracking-tighter uppercase mb-2 leading-none">
            Hoàn tất <span class="text-primary italic">đặt chỗ</span>
        </h1>
        <p class="text-teal-100 text-[10px] font-black uppercase tracking-[0.4em] opacity-80">Cảm ơn bạn đã tin tưởng {{ $settings['site_name'] ?? 'Tour Travel' }}</p>
    </div>
</header>

<!-- Results Section -->
<section class="pb-24 bg-slate-50 -mt-24 relative z-20">
    <div class="container max-w-5xl">
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden border border-slate-100">
            
            <!-- Success Banner -->
            <div class="bg-gradient-primary p-12 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-dark opacity-10"></div>
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-white rounded-[2rem] flex items-center justify-center text-primary mx-auto mb-6 shadow-xl animate-bounce">
                        <i class="fas fa-check text-3xl"></i>
                    </div>
                    <h1 class="text-4xl font-black text-white tracking-tighter uppercase mb-2 leading-none">Đặt tour thành công!</h1>
                    <p class="text-teal-100 text-[10px] font-black uppercase tracking-[0.4em] opacity-80">Cảm ơn bạn đã tin tưởng {{ $settings['site_name'] ?? 'Tour Travel' }}</p>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="p-8 md:p-12">
                <!-- Booking Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="flex items-center gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-primary transition-all">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm border border-slate-100">
                            <i class="fas fa-hashtag"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Mã đặt chỗ</p>
                            <p class="text-xs font-black text-slate-800 uppercase tracking-widest">{{ $booking->booking_code }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-primary transition-all">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm border border-slate-100">
                            <i class="fas fa-plane-departure text-sm"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Tên hành trình</p>
                            <p class="text-xs font-black text-slate-800 uppercase leading-none truncate">{{ $booking->departure->tour->title }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-primary transition-all">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm border border-slate-100">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Ngày khởi hành</p>
                            <p class="text-xs font-black text-slate-800 uppercase tracking-widest">{{ date('d / m / Y', strtotime($booking->departure->start_date)) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-primary transition-all">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm border border-slate-100">
                            <i class="fas fa-clock text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Thời lượng</p>
                            <p class="text-xs font-black text-slate-800 uppercase tracking-widest">{{ $booking->departure->tour->duration ?? '3 ngày 2 đêm' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-primary transition-all">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm border border-slate-100">
                            <i class="fas fa-bus text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Phương tiện</p>
                            <p class="text-xs font-black text-slate-800 uppercase tracking-widest">{{ $booking->departure->tour->transportation ?? 'Xe du lịch cao cấp' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-primary transition-all">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm border border-slate-100">
                            <i class="fas fa-users text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Số lượng khách</p>
                            <p class="text-xs font-black text-slate-800 uppercase tracking-widest">{{ $booking->passengers->count() }} người</p>
                        </div>
                    </div>
                </div>

                <!-- Registered Passengers Table -->
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 mb-12">
                    <h4 class="text-xs font-black text-slate-700 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fas fa-user-check text-primary"></i> Danh sách hành khách đã đăng ký
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($booking->passengers as $index => $passenger)
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-black text-xs">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900 uppercase m-0">{{ $passenger->name }}</p>
                                    <p class="text-[10px] text-slate-400 m-0">{{ $passenger->id_card ? 'CCCD: '.$passenger->id_card : 'Chưa cập nhật CCCD' }}</p>
                                </div>
                            </div>
                            <span class="text-[9px] bg-emerald-50 text-primary px-3 py-1 rounded-full font-black uppercase">Thành viên</span>
                        </div>
                        @endforeach
                    </div>
                    @if($booking->notes)
                    <div class="mt-6 p-4 bg-white rounded-2xl border border-slate-200 text-xs text-slate-600 italic">
                        <strong>Ghi chú của bạn:</strong> "{{ $booking->notes }}"
                    </div>
                    @endif
                </div>

                <!-- Next Steps Timeline -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm mb-10 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-6 h-full bg-blue-500"></div>
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-8 flex items-center gap-2">
                        <i class="fas fa-tasks text-blue-500"></i> Quy trình tiếp theo
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
                        <div class="text-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-black text-xs mx-auto mb-3">1</div>
                            <h5 class="text-[10px] font-black text-slate-800 uppercase mb-1">Đặt chỗ thành công</h5>
                            <p class="text-[10px] text-slate-500 m-0">Hệ thống ghi nhận mã đơn</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center font-black text-xs mx-auto mb-3">2</div>
                            <h5 class="text-[10px] font-black text-slate-800 uppercase mb-1">Thanh toán đơn hàng</h5>
                            <p class="text-[10px] text-slate-500 m-0">Chuyển khoản theo hướng dẫn</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-slate-300 text-slate-600 flex items-center justify-center font-black text-xs mx-auto mb-3">3</div>
                            <h5 class="text-[10px] font-black text-slate-800 uppercase mb-1">Xác nhận thanh toán</h5>
                            <p class="text-[10px] text-slate-500 m-0">Nhận vé điện tử qua Email/Zalo</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-slate-300 text-slate-600 flex items-center justify-center font-black text-xs mx-auto mb-3">4</div>
                            <h5 class="text-[10px] font-black text-slate-800 uppercase mb-1">HDV liên hệ</h5>
                            <p class="text-[10px] text-slate-500 m-0">Trước 24h ngày khởi hành</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="bg-dark p-8 md:p-10 rounded-[2rem] border-t-8 border-t-primary shadow-sm mb-10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-[60px] -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.3em] mb-6">Hướng dẫn thanh toán</h3>
                        <div class="flex justify-between items-end mb-10 border-b border-white/5 pb-8">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Số tiền cần thanh toán</p>
                                <h3 class="text-5xl font-black text-white tracking-tighter">{{ number_format($booking->total_price) }}đ</h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="space-y-4">
                                <div class="flex gap-4">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 text-primary flex items-center justify-center font-black">1</div>
                                    <p class="text-xs font-bold text-slate-400 leading-relaxed uppercase tracking-tight">Vui lòng chuyển khoản chính xác số tiền trên kèm mã đơn hàng vào tài khoản của chúng tôi.</p>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 text-primary flex items-center justify-center font-black">2</div>
                                    <p class="text-xs font-bold text-slate-400 leading-relaxed uppercase tracking-tight">Nội dung chuyển khoản: <span class="bg-primary/20 text-primary px-2 py-1 rounded-md">{{ $booking->booking_code }}</span></p>
                                </div>
                            </div>
                            <div class="bg-white/5 p-6 rounded-2xl border border-white/5 flex gap-6 items-center">
                                <div>
                                    <h5 class="text-[9px] font-black text-primary uppercase tracking-widest mb-4">Thông tin ngân hàng</h5>
                                    <p class="text-xs font-bold text-white uppercase tracking-widest mb-1">{{ $settings['bank_account_name'] ?? 'Chưa cập nhật' }} ({{ $settings['bank_name'] ?? 'Chưa cập nhật' }})</p>
                                    <p class="text-xl font-black text-white tracking-tighter">{{ $settings['bank_account_number'] ?? 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="bg-white p-2 rounded-xl">
                                    <img src="https://img.vietqr.io/image/{{ strtolower($settings['bank_name'] ?? 'vietcombank') }}-{{ $settings['bank_account_number'] ?? '' }}-compact.jpg?amount={{ $booking->total_price }}&addInfo={{ $booking->booking_code }}&accountName={{ urlencode($settings['bank_account_name'] ?? '') }}" 
                                         class="w-24 h-24 object-contain rounded-lg" alt="QR Code Thanh Toán">
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5 text-xs text-slate-300 flex items-center gap-4">
                            <i class="fas fa-headset text-primary text-lg"></i>
                            <span>Cần hỗ trợ khẩn cấp? Gọi hotline 24/7: <strong class="text-white font-black">{{ $settings['contact_phone'] ?? '0123 456 789' }}</strong> hoặc email <strong class="text-white">{{ $settings['contact_email'] ?? 'support@antigravitytravel.vn' }}</strong></span>
                        </div>

                        @if($booking->payment_status === 'Unpaid')
                        <div class="mt-8 pt-8 border-t border-white/10 text-center">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-4">Sau khi chuyển khoản thành công, vui lòng bấm nút dưới đây để chúng tôi xác nhận</p>
                            <form action="{{ route('public.bookings.confirmTransfer', $booking->booking_code) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary !px-8 !py-4 rounded-full shadow-lg shadow-emerald-500/20 hover:scale-105 transition-transform">
                                    <i class="fas fa-check-circle mr-2"></i> XÁC NHẬN ĐÃ CHUYỂN KHOẢN
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6 pt-10 border-t border-slate-100">
                    @if($booking->payment_status == 'Paid')
                    <a href="{{ route('public.tickets.show', $booking->id) }}" class="btn flex-1 py-5 rounded-[1.8rem] !text-xs group" style="background: linear-gradient(135deg, #10b981, #059669); color: white; box-shadow: 0 4px 15px rgba(16,185,129,.3);">
                        <i class="fas fa-qrcode mr-2"></i> XEM VÉ ĐIỆN TỬ
                    </a>
                    @endif
                    <a href="{{ route('public.account.bookings.history') }}" class="btn btn-primary flex-1 py-5 rounded-[1.8rem] !text-xs group">
                        XEM LỊCH SỬ ĐẶT TOUR <i class="fas fa-chevron-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline flex-1 py-5 rounded-[1.8rem] !text-xs border-slate-200 text-slate-400 hover:text-slate-800 hover:bg-slate-50">
                        VỀ TRANG CHỦ
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
