@extends('layouts.print')

@section('invoice_type', 'DANH SÁCH TOUR DU LỊCH')

@section('content')
<div class="mb-4 text-sm text-gray-600 italic">
    Toàn bộ danh sách Tour đang vận hành trên hệ thống.
</div>

<table class="w-full text-left border-collapse border border-gray-200">
    <thead>
        <tr class="bg-indigo-50 text-indigo-900 text-xs uppercase font-bold text-center">
            <th class="px-3 py-3 border border-gray-200">Mã</th>
            <th class="px-3 py-3 border border-gray-200 text-left">Tên Tour</th>
            <th class="px-3 py-3 border border-gray-200">Thời gian</th>
            <th class="px-3 py-3 border border-gray-200">Giá cơ bản</th>
            <th class="px-3 py-3 border border-gray-200">Trạng thái</th>
            <th class="px-3 py-3 border border-gray-200 text-left">Điểm đến tiêu biểu</th>
        </tr>
    </thead>
    <tbody class="text-sm text-gray-700">
        @foreach($tours as $tour)
        <tr>
            <td class="px-3 py-3 border border-gray-200 font-mono text-xs text-center uppercase">#T{{ str_pad($tour->id, 4, '0', STR_PAD_LEFT) }}</td>
            <td class="px-3 py-3 border border-gray-200 font-bold leading-tight">{{ $tour->title }}</td>
            <td class="px-3 py-3 border border-gray-200 text-center">{{ $tour->duration }}</td>
            <td class="px-3 py-3 border border-gray-200 text-center text-indigo-600 font-bold">{{ number_format($tour->base_price) }}đ</td>
            <td class="px-3 py-3 border border-gray-200 text-center uppercase text-[10px] font-bold">
                {{ $tour->is_active ? 'Đang bán' : 'Tạm ẩn' }}
            </td>
            <td class="px-3 py-3 border border-gray-200 text-xs">
                @foreach($tour->destinations as $dest)
                    {{ $dest->name }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-12 text-sm">
    <div class="bg-gray-50 p-4 border rounded-lg">
        <h4 class="font-bold text-indigo-900 mb-2 uppercase text-xs">Phân tích dữ liệu:</h4>
        <div class="grid grid-cols-3 gap-8">
            <div>
                <span class="text-gray-500">Tổng số tour:</span>
                <span class="font-bold ml-1">{{ $tours->count() }}</span>
            </div>
            <div>
                <span class="text-gray-500">Đang hoạt động:</span>
                <span class="font-bold ml-1 text-green-600">{{ $tours->where('is_active', 1)->count() }}</span>
            </div>
            <div>
                <span class="text-gray-500">Ngừng kinh doanh:</span>
                <span class="font-bold ml-1 text-red-500">{{ $tours->where('is_active', 0)->count() }}</span>
            </div>
        </div>
    </div>
</div>

<div class="mt-12 flex justify-between px-8 text-sm pt-8 border-t border-gray-200">
    <div class="text-center">
        <p class="font-bold mb-16 uppercase text-xs">Trưởng phòng kinh doanh</p>
        <p class="italic text-gray-400 font-medium">(Ký và đóng dấu)</p>
    </div>
    <div class="text-center">
        <p class="font-bold mb-16 uppercase text-xs">Người lập báo cáo</p>
        <p class="font-bold">ADMINISTRATOR</p>
    </div>
</div>
@endsection
