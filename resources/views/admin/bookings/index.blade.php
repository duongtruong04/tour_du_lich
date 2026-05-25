@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('actions')
<div class="flex space-x-2">
    <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank" class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg font-medium transition flex items-center shadow-sm">
        <i class="fas fa-file-pdf mr-2"></i> Xuất PDF
    </a>
    <a href="{{ route('admin.bookings.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center shadow-sm">
        <i class="fas fa-plus mr-2"></i> Đặt tại quầy
    </a>
</div>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="p-4 bg-gray-50 border-b border-gray-100">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm mã, tên khách..." 
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>
            <div>
                <select name="status" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                    <option value="">-- Trạng thái tour --</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Chờ duyệt (Pending)</option>
                    <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Đã xác nhận (Confirmed)</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Đã hủy (Cancelled)</option>
                </select>
            </div>
            <div>
                <select name="payment_status" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                    <option value="">-- Thanh toán --</option>
                    <option value="Unpaid" {{ request('payment_status') == 'Unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>Đã thanh toán</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex-1 text-sm font-bold">Lọc</button>
                <a href="{{ route('admin.bookings.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-center" title="Bỏ lọc"><i class="fas fa-sync-alt"></i></a>
            </div>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold text-center">
                    <th class="px-6 py-4 text-left">Mã Đơn</th>
                    <th class="px-6 py-4 text-left">Khách hàng</th>
                    <th class="px-6 py-4 text-left">Tour / Ngày đi</th>
                    <th class="px-6 py-4">Khách</th>
                    <th class="px-6 py-4">Tổng tiền</th>
                    <th class="px-6 py-4">Trạng thái</th>
                    <th class="px-6 py-4 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50 transition border-l-4 {{ $booking->status == 'Confirmed' ? 'border-l-green-500' : ($booking->status == 'Cancelled' ? 'border-l-red-500' : 'border-l-yellow-500') }}">
                    <td class="px-6 py-4 font-bold text-gray-900 text-sm">#{{ $booking->booking_code }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900 text-sm">{{ $booking->user->full_name }}</div>
                        <div class="text-[10px] text-gray-400">{{ $booking->user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium">{{ Str::limit($booking->departure->tour->title ?? 'N/A', 35) }}</div>
                        <div class="text-[10px] text-indigo-600 font-bold uppercase">{{ $booking->departure->start_date }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-gray-100 text-gray-700 w-8 h-8 rounded-full inline-flex items-center justify-center font-bold text-xs">
                            {{ count($booking->passengers) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-indigo-700 text-center">{{ number_format($booking->total_price) }}đ</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col space-y-1 items-center">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase text-center w-full 
                                @if($booking->status == 'Confirmed') bg-green-100 text-green-700 
                                @elseif($booking->status == 'Pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ $booking->status }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase text-center w-full
                                {{ $booking->payment_status == 'Paid' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $booking->payment_status }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-indigo-600 hover:text-indigo-800" title="Chi tiết"><i class="fas fa-eye text-lg"></i></a>
                        <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="text-blue-600 hover:text-blue-800" title="Sửa"><i class="fas fa-edit text-lg"></i></a>
                        <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Xóa đơn hàng này?')" title="Xóa"><i class="fas fa-trash text-lg"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">Không tìm thấy đơn hàng nào phù hợp.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100 bg-gray-50">
        {{ $bookings->appends(request()->query())->links() }}
    </div>
</div>
@endsection
