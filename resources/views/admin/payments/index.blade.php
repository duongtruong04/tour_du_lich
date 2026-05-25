@extends('layouts.admin')

@section('title', 'Quản lý thanh toán')

@section('content')
<div class="card p-0 overflow-hidden">
    <!-- Header/Filter -->
    <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h4 class="text-lg font-black text-slate-800 tracking-tighter uppercase">Lịch sử giao dịch</h4>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Theo dõi doanh thu và trạng thái thanh toán từ khách hàng</p>
        </div>
        <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-wrap items-center gap-4 w-full md:w-auto">
            <div class="flex-1 md:w-64 relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Mã giao dịch/Khách hàng..." class="w-full bg-slate-50 border-none rounded-xl py-2.5 pl-11 pr-4 text-xs font-bold focus:ring-1 focus:ring-primary transition-all">
            </div>
            <div class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-slate-50 border-none rounded-xl px-3 py-2.5 text-[10px] font-bold text-slate-600 focus:ring-1 focus:ring-primary transition-all">
                <span class="text-slate-300">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-slate-50 border-none rounded-xl px-3 py-2.5 text-[10px] font-bold text-slate-600 focus:ring-1 focus:ring-primary transition-all">
            </div>
            <select name="method" class="bg-slate-50 border-none rounded-xl px-4 py-2.5 text-xs font-bold text-slate-600 focus:ring-1 focus:ring-primary transition-all">
                <option value="">Phương thức</option>
                <option value="VNPay" {{ request('method') == 'VNPay' ? 'selected' : '' }}>VNPay</option>
                <option value="MoMo" {{ request('method') == 'MoMo' ? 'selected' : '' }}>MoMo</option>
                <option value="Cash" {{ request('method') == 'Cash' ? 'selected' : '' }}>Tiền mặt</option>
            </select>
            <button type="submit" class="btn btn-primary !p-3 rounded-xl shadow-none"><i class="fas fa-filter"></i></button>
            @if(request()->anyFilled(['search', 'method', 'start_date', 'end_date']))
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline !p-3 rounded-xl border-none bg-slate-100 text-slate-400 hover:bg-slate-200"><i class="fas fa-sync-alt"></i></a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Thời gian</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Khách hàng</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Mã Giao dịch</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Phương thức</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Số tiền</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($payments as $payment)
                <tr class="hover:bg-slate-50/30 transition-colors">
                    <td class="p-5">
                        <p class="text-[11px] font-black text-slate-700 leading-none mb-1">{{ date('d/m/Y', strtotime($payment->payment_date)) }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ date('H:i', strtotime($payment->payment_date)) }}</p>
                    </td>
                    <td class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-primary text-[10px] font-black uppercase">
                                {{ substr($payment->booking->user->full_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-slate-800 leading-none">{{ $payment->booking->user->full_name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase">Đơn: #{{ $payment->booking->booking_code }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-5">
                        <span class="text-xs font-black text-slate-600 uppercase tracking-widest">{{ $payment->transaction_id ?? 'CHƯA CÓ' }}</span>
                    </td>
                    <td class="p-5 text-center">
                        <span class="px-3 py-1.5 rounded-lg border text-[9px] font-black uppercase tracking-widest bg-slate-50 text-slate-500 border-slate-100">
                             {{ $payment->method }}
                        </span>
                    </td>
                    <td class="p-5 text-right">
                        <span class="text-xs font-black text-emerald-600 tracking-tight">{{ number_format($payment->amount) }}đ</span>
                    </td>
                    <td class="p-5 text-right">
                         <a href="{{ route('admin.payments.show', $payment) }}" class="text-primary hover:underline text-[9px] font-black uppercase tracking-widest">Chi tiết <i class="fas fa-chevron-right ml-1"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-20 text-center">
                        <i class="fas fa-search text-4xl text-slate-200 mb-4 block"></i>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Không tìm thấy giao dịch nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
    <div class="p-8 border-t border-slate-50">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection
