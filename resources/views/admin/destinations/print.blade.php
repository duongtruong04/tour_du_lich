@extends('layouts.print')

@section('invoice_type', 'DANH SÁCH ĐIỂM ĐẾN')

@section('content')
<div class="mb-4 text-sm text-gray-600 italic">
    Kết quả phù hợp với các điều kiện lọc tại thời điểm xuất báo cáo.
</div>

<table class="w-full text-left border-collapse border border-gray-200">
    <thead>
        <tr class="bg-indigo-50 text-indigo-900 text-xs uppercase font-bold">
            <th class="px-4 py-3 border border-gray-200">ID</th>
            <th class="px-4 py-3 border border-gray-200">Tên điểm đến</th>
            <th class="px-4 py-3 border border-gray-200">Vị trí</th>
            <th class="px-4 py-3 border border-gray-200 w-1/2">Mô tả</th>
            <th class="px-4 py-3 border border-gray-200">Ngày tạo</th>
        </tr>
    </thead>
    <tbody class="text-sm text-gray-700">
        @foreach($destinations as $dest)
        <tr>
            <td class="px-4 py-3 border border-gray-200 font-mono text-xs">#{{ $dest->id }}</td>
            <td class="px-4 py-3 border border-gray-200 font-bold">{{ $dest->name }}</td>
            <td class="px-4 py-3 border border-gray-200">{{ $dest->location }}</td>
            <td class="px-4 py-3 border border-gray-200 text-xs leading-tight">{{ Str::limit($dest->description, 250) }}</td>
            <td class="px-4 py-3 border border-gray-200">{{ $dest->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-8 grid grid-cols-2 gap-8 text-sm">
    <div>
        <h4 class="font-bold border-b-2 border-indigo-100 pb-1 mb-2">Ghi chú vận hành:</h4>
        <p class="text-xs text-gray-500 text-sm">Bản in này phục vụ mục đích kiểm kê và báo cáo nội bộ. Vui lòng bảo quản tài liệu cẩn thận.</p>
    </div>
    <div class="text-center pt-8">
        <p class="font-bold">Người lập biểu</p>
        <p class="text-xs text-gray-400 mt-12">(Ký và ghi rõ họ tên)</p>
    </div>
</div>
@endsection
