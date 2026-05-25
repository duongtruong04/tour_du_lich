@extends('layouts.admin')

@section('title', 'Danh sách Điểm đến')

@section('content')
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 bg-gray-50/30 flex-wrap">
        <form action="{{ route('admin.destinations.index') }}" method="GET" class="flex flex-col md:flex-row flex-wrap gap-4 w-full xl:w-auto">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Tìm tên, địa điểm..." 
                    class="pl-12 pr-4 py-3 bg-white border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 shadow-sm w-full md:w-64 font-medium text-sm">
            </div>
            <select name="location" class="px-4 py-3 bg-white border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 shadow-sm font-medium text-sm">
                <option value="">Tất cả khu vực</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-500/20">Lọc</button>
        </form>
        <div class="flex gap-3 w-full xl:w-auto flex-wrap">
            <a href="{{ route('admin.destinations.index', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all flex-1 md:flex-none text-center">
                <i class="fas fa-file-pdf mr-2"></i> Xuất PDF
            </a>
            <a href="{{ route('admin.destinations.create') }}" class="px-6 py-3 bg-teal-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-500/20 flex-1 md:flex-none text-center">
                <i class="fas fa-plus mr-2"></i> Thêm mới
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-slate-400 text-[10px] uppercase font-black border-b border-gray-50">
                    <th class="px-8 py-6">Hình ảnh</th>
                    <th class="px-8 py-6">Tên điểm đến</th>
                    <th class="px-8 py-6">Khu vực</th>
                    <th class="px-8 py-6">Mô tả</th>
                    <th class="px-8 py-6 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs font-bold">
                @forelse($destinations as $destination)
                <tr class="hover:bg-teal-50/30 transition-colors group">
                    <td class="px-8 py-4">
                        <div class="w-16 h-12 rounded-xl bg-slate-100 overflow-hidden shadow-sm">
                            @if($destination->image_path)
                                <img src="{{ $destination->image_url }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-8 py-4 text-slate-900 font-black uppercase tracking-tight">{{ $destination->name }}</td>
                    <td class="px-8 py-4">
                        <span class="px-3 py-1 bg-teal-50 text-teal-600 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $destination->location }}</span>
                    </td>
                    <td class="px-8 py-4 text-slate-500 font-medium max-w-xs truncate">{{ $destination->description }}</td>
                    <td class="px-8 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.destinations.edit', $destination) }}" class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" onsubmit="return confirm('Xác nhận xóa điểm đến này?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold italic uppercase text-[10px] tracking-widest">Không tìm thấy bản ghi nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-8 border-t border-gray-50 bg-gray-50/30">
        {{ $destinations->links() }}
    </div>
</div>
@endsection
