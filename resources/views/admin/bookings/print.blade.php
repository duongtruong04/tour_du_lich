@extends('layouts.print')

@section('invoice_type', 'PHIẾU XÁC NHẬN ĐẶT TOUR')

@section('content')
<div class="flex justify-between mb-8">
    <div class="w-1/2">
        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-indigo-50 pb-1">Thông tin khách hàng:</h4>
        <div class="text-sm font-bold text-gray-900 border-l-4 border-indigo-500 pl-3">
            <div class="uppercase">{{ $bookings->first()->user->full_name ?? 'DANH SÁCH TỔNG HỢP' }}</div>
            <div class="text-gray-500 text-xs font-medium">{{ $bookings->first()->user->email ?? '' }}</div>
            <div class="text-gray-500 text-xs font-medium">SĐT: {{ $bookings->first()->user->phone ?? '---' }}</div>
        </div>
    </div>
    <div class="text-right w-1/2">
        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-indigo-50 pb-1 inline-block">Mã Booking:</h4>
        <div class="text-2xl font-black text-indigo-900">#{{ $bookings->first()->booking_code ?? 'REPORTS' }}</div>
        <div class="text-[10px] text-gray-400 mt-1 uppercase">Trạng thái: {{ $bookings->first()->status ?? 'N/A' }}</div>
    </div>
</div>

<table class="w-full text-left border-collapse border border-gray-200 mt-8">
    <thead>
        <tr class="bg-indigo-50 text-indigo-900 text-[10px] uppercase font-bold text-center">
            <th class="px-3 py-3 border border-gray-200">Mã Đơn</th>
            <th class="px-3 py-3 border border-gray-200 text-left">Tour du lịch / Lịch đi</th>
            <th class="px-3 py-3 border border-gray-200">Số khách</th>
            <th class="px-3 py-3 border border-gray-200">Đơn giá</th>
            <th class="px-3 py-3 border border-gray-200">Thành tiền</th>
        </tr>
    </thead>
    <tbody class="text-sm text-gray-700 font-medium">
        @foreach($bookings as $booking)
        <tr>
            <td class="px-3 py-4 border border-gray-200 font-mono text-xs text-center uppercase tracking-tighter">#{{ $booking->booking_code }}</td>
            <td class="px-3 py-4 border border-gray-200 leading-tight">
                <div class="font-bold text-gray-900 text-base mb-1">{{ $booking->departure->tour->title }}</div>
                <div class="text-indigo-600 text-[10px] font-black uppercase tracking-widest">Khởi hành: {{ $booking->departure->start_date }}</div>
            </td>
            <td class="px-3 py-4 border border-gray-200 text-center text-lg font-bold">{{ count($booking->passengers) }}</td>
            <td class="px-3 py-4 border border-gray-200 text-right">{{ number_format($booking->total_price / count($booking->passengers)) }}đ</td>
            <td class="px-3 py-4 border border-gray-200 text-right font-black text-indigo-700 text-lg">{{ number_format($booking->total_price) }}đ</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="bg-gray-50">
            <td colspan="4" class="px-6 py-4 text-right border border-gray-200 uppercase text-xs font-black tracking-widest">Tổng cộng thanh toán:</td>
            <td class="px-6 py-4 text-right border border-gray-200 font-black text-3xl text-indigo-900">{{ number_format($bookings->sum('total_price')) }}đ</td>
        </tr>
    </tfoot>
</table>

<div class="mt-12 grid grid-cols-2 gap-20 text-center px-10">
    <div>
        <h4 class="font-black text-gray-500 mb-20 uppercase text-xs tracking-tighter">Xác nhận của Khách hàng</h4>
        <div class="border-t border-gray-300 pt-2 font-bold uppercase text-xs">{{ $bookings->first()->user->full_name ?? '' }}</div>
    </div>
    <div>
        <h4 class="font-black text-gray-500 mb-20 uppercase text-xs tracking-tighter">Đại diện {{ $settings['site_name'] ?? 'Tour Travel' }}</h4>
        <div class="border-t border-gray-300 pt-2 font-bold uppercase text-xs">Người lập phiếu: ADMINISTRATOR</div>
    </div>
</div>

<div class="mt-16 bg-gray-50 p-6 rounded-xl border-2 border-dashed border-gray-200 text-[10px] text-gray-400">
    <h5 class="font-bold text-gray-600 mb-2 uppercase">Điều khoản & Lưu ý:</h5>
    <ul class="list-disc pl-4 space-y-1">
        <li>Quý khách vui lòng có mặt tại điểm tập kết đúng giờ hẹn để bắt hành trình.</li>
        <li>Hóa đơn này kiêm phiếu thu nếu đã thanh toán Full (Trạng thái: Paid).</li>
        <li>Trong trường hợp hủy tour, vui lòng liên hệ hotline 1900 6789 trước ít nhất 7 ngày.</li>
    </ul>
</div>
@endsection
