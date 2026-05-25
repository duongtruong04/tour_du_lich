@extends('layouts.admin')

@section('title', 'Kiểm Tra Vé Điện Tử')

@section('content')
<div class="max-w-md mx-auto py-8 px-4">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4">
            <i class="fas fa-qrcode"></i>
        </div>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Xác Nhận Check-in</h1>
        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-2">Hệ thống kiểm soát vé tour</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 text-sm font-bold">
            <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center gap-3 text-sm font-bold">
            <i class="fas fa-exclamation-triangle text-lg"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Mã Vé</p>
                <p class="text-lg font-mono font-black text-slate-800">{{ $passenger->ticket_code }}</p>
            </div>
            @if($passenger->checked_in_at)
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] font-black uppercase tracking-widest">Đã Check-in</span>
            @else
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-black uppercase tracking-widest">Chưa Check-in</span>
            @endif
        </div>

        <div class="p-6 space-y-6">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tour Khởi Hành</p>
                <p class="text-sm font-bold text-slate-800 uppercase">{{ $passenger->booking->departure->tour->title }}</p>
                <p class="text-xs text-primary font-bold mt-1"><i class="far fa-calendar-alt"></i> Ngày: {{ date('d/m/Y', strtotime($passenger->booking->departure->start_date)) }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Hành Khách</p>
                    <p class="text-sm font-bold text-slate-800 uppercase">{{ $passenger->name }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Giấy Tờ</p>
                    <p class="text-sm font-bold text-slate-800">{{ $passenger->id_card ?? 'Không có' }}</p>
                </div>
            </div>

            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Người Đặt Tour (Đại diện)</p>
                <p class="text-sm font-bold text-slate-800">{{ $passenger->booking->user->full_name }} ({{ $passenger->booking->user->phone }})</p>
            </div>
            
            @if($passenger->checked_in_at)
            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 flex items-center gap-3">
                <i class="fas fa-info-circle text-blue-500"></i>
                <p class="text-xs text-blue-700 font-bold m-0">Đã check-in lúc: <br>{{ $passenger->checked_in_at->format('H:i:s - d/m/Y') }}</p>
            </div>
            @endif
        </div>

        @if(!$passenger->checked_in_at)
        <div class="p-6 bg-slate-50 border-t border-slate-100 text-center">
            <form action="{{ route('admin.tickets.checkin', $passenger->ticket_code) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary w-full py-4 rounded-xl text-sm font-black uppercase tracking-widest shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-check-circle mr-2"></i> Xác nhận lên xe
                </button>
            </form>
        </div>
        @else
        <div class="p-6 bg-slate-50 border-t border-slate-100 text-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline w-full py-4 rounded-xl text-sm font-black uppercase tracking-widest">
                Về Trang Chủ
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
