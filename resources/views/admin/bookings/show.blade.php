@extends('layouts.admin')

@section('title', 'Chi tiết Đơn hàng')

@section('content')
{{-- Header Button Bar --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 no-print mb-6">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Chi tiết <span class="text-indigo-600">Đơn hàng</span></h1>
        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Quản lý và in hóa đơn thanh toán</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.bookings.print', $booking->id) }}" target="_blank" class="px-5 py-2.5 bg-slate-800 hover:bg-black text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all text-center flex items-center gap-1.5 shadow-md">
            <i class="fas fa-print text-xs"></i> In hóa đơn
        </a>
        <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="px-5 py-2.5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 text-center flex items-center gap-1.5">
            <i class="fas fa-edit text-xs"></i> Cập nhật
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="px-5 py-2.5 bg-slate-200 text-slate-500 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-300 transition-all text-center">
            Quay lại
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <!-- Main Booking Info -->
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 uppercase">Đơn hàng #{{ $booking->booking_code }}</h2>
                    <p class="text-gray-400 text-sm mt-1">Ngày đặt: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="px-4 py-1 rounded-full text-xs font-black uppercase 
                        @if($booking->status == 'Confirmed') bg-green-100 text-green-700 
                        @elseif($booking->status == 'Pending') bg-yellow-100 text-yellow-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ $booking->status }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 py-6 border-t border-b border-gray-50 mb-6">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Thông tin khách hàng</h4>
                    <div class="font-bold text-gray-900 border-l-4 border-indigo-500 pl-3">
                        <div class="text-lg">{{ $booking->user->full_name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ $booking->user->email }}</div>
                        <div class="text-sm font-medium text-gray-500">SĐT: {{ $booking->user->phone ?? 'Chưa cập nhật' }}</div>
                    </div>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Thông tin Tour</h4>
                    <div class="font-bold text-gray-900 border-l-4 border-indigo-500 pl-3">
                        <div class="text-lg">{{ $booking->departure->tour->title }}</div>
                        <div class="text-sm text-indigo-600 uppercase">Khởi hành: {{ $booking->departure->start_date }}</div>
                        <div class="text-sm font-medium text-gray-500">Mã lịch: #DEP-{{ $booking->departure->id }}</div>
                    </div>
                </div>
            </div>

            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Danh sách hành khách</h4>
            <div class="overflow-hidden border rounded-lg">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[10px]">
                        <tr>
                            <th class="px-4 py-2">STT</th>
                            <th class="px-4 py-2">Họ tên</th>
                            <th class="px-4 py-2">Số CMND/CCCD</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($booking->passengers as $index => $passenger)
                        <tr>
                            <td class="px-4 py-3 text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $passenger->name }}</td>
                            <td class="px-4 py-3 text-gray-600 font-mono">{{ $passenger->id_card ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($booking->notes)
        <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-100">
            <h4 class="text-[10px] font-black text-yellow-600 uppercase tracking-widest mb-2 font-bold">Ghi chú đơn hàng</h4>
            <p class="text-sm text-yellow-800 italic">{{ $booking->notes }}</p>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-6 flex items-center border-b pb-4">
                <i class="fas fa-money-bill-wave mr-2 text-green-500"></i> Thanh toán
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Trạng thái:</span>
                    <span class="font-bold {{ $booking->payment_status == 'Paid' ? 'text-green-600' : 'text-red-500' }}">
                        {{ $booking->payment_status == 'Paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-4 border-t border-b border-gray-50">
                    <span class="text-gray-900 font-bold uppercase text-xs">Tổng cộng:</span>
                    <span class="text-2xl font-black text-indigo-700">{{ number_format($booking->total_price) }}đ</span>
                </div>
                
                @forelse($booking->payments as $payment)
                <div class="bg-gray-50 p-3 rounded-lg text-xs">
                    <div class="flex justify-between mb-1">
                        <span class="font-bold text-gray-700">Ngày: {{ $payment->payment_date }}</span>
                        <span class="text-indigo-600 font-bold">{{ number_format($payment->amount) }}đ</span>
                    </div>
                    <div class="text-gray-400">Phương thức: {{ $payment->method }}</div>
                </div>
                @empty
                <p class="text-xs text-center text-gray-400 italic py-2">Chưa ghi nhận lịch sử thanh toán.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-indigo-900 text-white p-6 rounded-xl shadow-lg relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <i class="fas fa-file-invoice text-8xl"></i>
            </div>
            <h3 class="font-bold mb-4 uppercase text-xs tracking-widest text-indigo-300">Tóm tắt dịch vụ</h3>
            <ul class="space-y-3 text-sm">
                <li class="flex items-center"><i class="fas fa-check-circle mr-2 text-indigo-400"></i> Bảo hiểm du lịch tối đa 100tr</li>
                <li class="flex items-center"><i class="fas fa-check-circle mr-2 text-indigo-400"></i> Hướng dẫn viên suốt tuyến</li>
                <li class="flex items-center"><i class="fas fa-check-circle mr-2 text-indigo-400"></i> Vé tham quan tất cả các điểm</li>
            </ul>
        </div>
    </div>
</div>
@endsection
