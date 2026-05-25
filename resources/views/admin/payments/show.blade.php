@extends('layouts.admin')

@section('title', 'Chi tiết giao dịch #' . $payment->transaction_id)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Payment Header -->
    <div class="card p-0 overflow-hidden mb-8 border-none shadow-2xl">
        <div class="p-10 bg-gradient-to-br from-emerald-600 to-teal-800 text-white relative">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-8">
                    <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-xl text-[10px] font-black uppercase tracking-[0.2em]">Transaction Details</span>
                    <span class="text-[10px] font-black uppercase tracking-widest opacity-70">{{ date('d M, Y - H:i', strtotime($payment->payment_date)) }}</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black tracking-tighter mb-2 uppercase">{{ number_format($payment->amount) }}đ</h2>
                <p class="text-emerald-100 font-bold uppercase tracking-widest text-[10px]">Mã giao dịch: {{ $payment->transaction_id ?? 'N/A' }}</p>
            </div>
            <!-- Decorative icon -->
            <i class="fas fa-file-invoice-dollar absolute right-10 bottom-10 text-8xl text-white/10"></i>
        </div>
        
        <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-12 bg-white">
            <div>
                <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Thông tin khách hàng</h5>
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-3xl bg-slate-50 flex items-center justify-center text-primary text-xl font-black shadow-inner">
                        {{ substr($payment->booking->user->full_name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-lg font-black text-slate-800 leading-tight">{{ $payment->booking->user->full_name }}</p>
                        <p class="text-xs font-bold text-slate-400 mt-1">{{ $payment->booking->user->email }}</p>
                        <p class="text-[10px] font-black text-primary uppercase tracking-widest mt-2"><i class="fas fa-phone-alt mr-1"></i> {{ $payment->booking->user->phone ?? '---' }}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Phương thức & Trạng thái</h5>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Phương thức</span>
                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-black text-slate-800 uppercase tracking-widest shadow-sm">{{ $payment->method }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Trạng thái</span>
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest"><i class="fas fa-check-circle mr-1"></i> Đã thanh toán</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Linked Booking Information -->
    <div class="card p-0 overflow-hidden border-none shadow-xl">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <h5 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em]">Thông tin đặt tour liên quan</h5>
            <a href="{{ route('admin.bookings.show', $payment->booking) }}" class="text-[9px] font-black text-primary uppercase tracking-widest hover:underline">Xem đơn hàng <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
        <div class="p-10">
            <div class="flex gap-8 mb-10">
                <div class="w-1/3 aspect-[4/3] rounded-[2rem] overflow-hidden bg-slate-100 shadow-lg">
                    @php $tourImage = $payment->booking->departure->tour->images->first(); @endphp
                    @if($tourImage)
                        <img src="{{ $tourImage->image_url }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fas fa-image text-3xl"></i></div>
                    @endif
                </div>
                <div class="flex-1 py-2">
                    <span class="px-3 py-1 bg-teal-50 text-primary rounded-lg text-[8px] font-black uppercase tracking-widest mb-4 inline-block">Tour #{{ $payment->booking->departure->tour->id }}</span>
                    <h4 class="text-xl font-black text-slate-800 uppercase tracking-tighter leading-tight mb-4">{{ $payment->booking->departure->tour->title }}</h4>
                    <div class="flex items-center gap-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <span><i class="far fa-calendar-alt text-primary mr-2"></i> {{ date('d/m/Y', strtotime($payment->booking->departure->start_date)) }}</span>
                        <span><i class="far fa-user text-primary mr-2"></i> {{ count($payment->booking->passengers) }} Khách</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-[2.5rem] p-8 border border-slate-100">
                <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 text-center">Danh sách khách đi tour</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($payment->booking->passengers as $passenger)
                        <div class="p-4 bg-white rounded-2xl border border-slate-100 flex items-center gap-4">
                            <i class="fas fa-user-circle text-slate-200 text-xl"></i>
                            <div>
                                <p class="text-xs font-black text-slate-700 leading-none mb-1">{{ $passenger->name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">CMND/CCCD: {{ $passenger->id_card ?? '---' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="md:col-span-2 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest py-4 italic">Không có thông tin hành khách.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10 flex justify-center">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline border-slate-200 text-slate-400 hover:text-dark px-10 rounded-2xl !text-[10px] py-4">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
        </a>
    </div>
</div>
@endsection
