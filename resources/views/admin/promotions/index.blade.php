@extends('layouts.admin')

@section('title', 'Quản lý khuyến mãi')

@section('content')
<div class="card p-0 overflow-hidden mb-6">
    <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h4 class="text-lg font-black text-slate-800 tracking-tighter uppercase">Danh sách khuyến mãi</h4>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Quản lý mã giảm giá và chiến dịch ưu đãi</p>
        </div>
        <form action="{{ route('admin.promotions.index') }}" method="GET" class="flex flex-wrap items-center gap-4 w-full md:w-auto">
            <div class="flex-1 md:w-48 relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Mã code..." class="w-full bg-slate-50 border-none rounded-xl py-2.5 pl-11 pr-4 text-xs font-bold focus:ring-1 focus:ring-primary transition-all">
            </div>
            <select name="discount_type" class="bg-slate-50 border-none rounded-xl px-4 py-2.5 text-xs font-bold text-slate-600 focus:ring-1 focus:ring-primary transition-all">
                <option value="">Loại giảm</option>
                <option value="Percentage" {{ request('discount_type') == 'Percentage' ? 'selected' : '' }}>Phần trăm</option>
                <option value="Fixed" {{ request('discount_type') == 'Fixed' ? 'selected' : '' }}>Cố định</option>
            </select>
            <select name="status" class="bg-slate-50 border-none rounded-xl px-4 py-2.5 text-xs font-bold text-slate-600 focus:ring-1 focus:ring-primary transition-all">
                <option value="">Trạng thái</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang chạy</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
            </select>
            <button type="submit" class="btn btn-primary !p-3 rounded-xl shadow-none"><i class="fas fa-filter"></i></button>
            @if(request()->anyFilled(['search', 'discount_type', 'status']))
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline !p-3 rounded-xl border-none bg-slate-100 text-slate-400 hover:bg-slate-200"><i class="fas fa-sync-alt"></i></a>
            @endif
            <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary !text-[10px] !py-3 rounded-xl whitespace-nowrap ml-2">
                <i class="fas fa-plus mr-2"></i> Thêm mới
            </a>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold text-center">
                    <th class="px-6 py-4 text-left">Mã CODE</th>
                    <th class="px-6 py-4">Giá trị giảm</th>
                    <th class="px-6 py-4">Loại</th>
                    <th class="px-6 py-4">Ngày hết hạn</th>
                    <th class="px-6 py-4">Lượt dùng</th>
                    <th class="px-6 py-4 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($promotions as $promo)
                <tr class="hover:bg-gray-50 transition border-l-4 border-l-indigo-500">
                    <td class="px-6 py-4">
                        <div class="font-black text-indigo-700 bg-indigo-50 px-3 py-1 rounded inline-block font-mono tracking-widest">{{ $promo->code }}</div>
                    </td>
                    <td class="px-6 py-4 text-center font-bold text-gray-900">
                        {{ number_format($promo->discount_value) }}{{ $promo->discount_type == 'Percentage' ? '%' : 'đ' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-[10px] font-bold uppercase {{ $promo->discount_type == 'Percentage' ? 'text-blue-500' : 'text-green-500' }}">
                            {{ $promo->discount_type == 'Percentage' ? 'Phần trăm' : 'Cố định' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-medium">
                        <span class="{{ \Carbon\Carbon::parse($promo->expiry_date)->isPast() ? 'text-red-500' : 'text-gray-600' }}">
                            {{ \Carbon\Carbon::parse($promo->expiry_date)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-400">
                        {{ $promo->used_count ?? 0 }} / {{ $promo->usage_limit }}
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.promotions.edit', $promo->id) }}" class="text-blue-600 hover:text-blue-800" title="Sửa"><i class="fas fa-edit"></i></a>
                        @if(Auth::user()->role_id == 1)
                        <form action="{{ route('admin.promotions.destroy', $promo->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Xóa mã này?')" title="Xóa"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">Chưa có mã khuyến mãi nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100 bg-gray-50">
        {{ $promotions->appends(request()->query())->links() }}
    </div>
</div>
@endsection
