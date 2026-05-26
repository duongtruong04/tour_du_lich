@extends('layouts.admin')

@section('title', 'Cập nhật Đơn hàng')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <div class="mb-8 border-b pb-4 flex justify-between items-end">
            <div>
                <h2 class="text-xl font-bold text-gray-900 uppercase">Đơn hàng #{{ $booking->booking_code }}</h2>
                <p class="text-sm text-gray-500">Khách hàng: {{ $booking->user->full_name }}</p>
            </div>
            <div class="text-right">
                <span class="text-2xl font-black text-indigo-700">{{ number_format($booking->total_price) }}đ</span>
            </div>
        </div>

        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2 font-bold uppercase text-[10px] tracking-widest text-primary">Trạng thái Tour</label>
                    <select name="status" class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none transition font-bold" @if($booking->status == 'Cancelled') disabled @endif>
                        <option value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Chờ duyệt (Pending)</option>
                        <option value="Confirmed" {{ $booking->status == 'Confirmed' ? 'selected' : '' }}>Đã xác nhận (Confirmed)</option>
                        <option value="Cancelled" {{ $booking->status == 'Cancelled' ? 'selected' : '' }}>Đã hủy (Cancelled)</option>
                    </select>
                    @if($booking->status == 'Cancelled')
                    <input type="hidden" name="status" value="Cancelled">
                    <p class="text-xs text-red-500 mt-2 italic">* Đơn hàng đã hủy không thể thay đổi trạng thái.</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2 font-bold uppercase text-[10px] tracking-widest text-primary">Trạng thái Thanh toán</label>
                    <div class="flex items-center space-x-6 bg-gray-50 p-4 rounded-lg">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="payment_status" value="Unpaid" {{ $booking->payment_status == 'Unpaid' ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary">
                            <span class="text-sm font-bold text-gray-700 uppercase">Chưa thanh toán</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="payment_status" value="Paid" {{ $booking->payment_status == 'Paid' ? 'checked' : '' }} class="w-4 h-4 text-green-600 focus:ring-green-500">
                            <span class="text-sm font-bold text-green-700 uppercase">Đã thanh toán</span>
                        </label>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2 font-bold uppercase text-[10px] tracking-widest text-primary">Ghi chú quản lý</label>
                    <textarea name="notes" rows="4" placeholder="Nhập ghi chú cho đơn hàng này..." 
                        class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none transition">{{ old('notes', $booking->notes) }}</textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t mt-8">
                    <a href="{{ route('admin.bookings.index') }}" class="px-6 py-3 border rounded-lg hover:bg-gray-50 transition font-bold uppercase text-xs text-gray-400">Hủy</a>
                    <button type="submit" class="px-12 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg font-bold transition shadow-lg shadow-primary/20 uppercase text-xs tracking-widest">
                        Lưu Thay Đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
