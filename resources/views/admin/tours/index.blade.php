@extends('layouts.admin')

@section('title', 'Quản lý Tour')

@section('content')
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 bg-gray-50/30">
        <form action="{{ route('admin.tours.index') }}" method="GET" class="tour-toolbar">
            <div class="tour-toolbar-filters">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Tìm tên tour..." 
                        class="pl-9 pr-3 py-2.5 bg-white border-0 rounded-xl focus:ring-2 focus:ring-teal-500 shadow-sm font-medium text-sm w-full">
                </div>
                <div class="flex items-center gap-1.5">
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Giá từ" class="w-20 px-2 py-2.5 bg-white border-0 rounded-xl focus:ring-2 focus:ring-teal-500 shadow-sm font-medium text-sm">
                    <span class="text-slate-300 text-xs">—</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Đến" class="w-20 px-2 py-2.5 bg-white border-0 rounded-xl focus:ring-2 focus:ring-teal-500 shadow-sm font-medium text-sm">
                </div>
                <select name="is_active" class="px-3 py-2.5 bg-white border-0 rounded-xl focus:ring-2 focus:ring-teal-500 shadow-sm font-medium text-sm">
                    <option value="">Trạng thái</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Đang mở</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Đã đóng</option>
                </select>
                <button type="submit" class="px-4 py-2.5 bg-teal-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-500/20 whitespace-nowrap">Lọc</button>
                @if(request()->anyFilled(['search', 'is_active', 'min_price', 'max_price']))
                    <a href="{{ route('admin.tours.index') }}" class="px-3 py-2.5 bg-slate-100 text-slate-400 rounded-xl hover:bg-slate-200 transition-all" title="Reset"><i class="fas fa-sync-alt text-xs"></i></a>
                @endif
            </div>
            <div class="tour-toolbar-actions">
                <a href="{{ route('admin.tours.index', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all text-center whitespace-nowrap">
                    <i class="fas fa-file-pdf mr-1"></i> Xuất Báo Cáo
                </a>
                <a href="{{ route('admin.tours.create') }}" class="px-4 py-2.5 bg-teal-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-500/20 text-center whitespace-nowrap">
                    <i class="fas fa-plus mr-1"></i> Tạo mới Tour
                </a>
            </div>
        </form>
    </div>

    @section('styles')
    <style>
        .tour-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: nowrap;
        }
        .tour-toolbar-filters {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1 1 auto;
            min-width: 0;
            flex-wrap: nowrap;
        }
        .tour-toolbar-filters .relative {
            flex: 1 1 140px;
            min-width: 120px;
            max-width: 200px;
        }
        .tour-toolbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 0 0 auto;
            margin-left: auto;
        }
        /* Tablet: allow filter group to wrap internally */
        @media (max-width: 1280px) {
            .tour-toolbar {
                flex-wrap: wrap;
            }
            .tour-toolbar-filters {
                flex: 1 1 100%;
                flex-wrap: wrap;
            }
            .tour-toolbar-actions {
                flex: 0 0 auto;
                margin-left: auto;
            }
        }
        /* Mobile: stack everything */
        @media (max-width: 640px) {
            .tour-toolbar-filters {
                flex-direction: column;
                align-items: stretch;
            }
            .tour-toolbar-filters .relative {
                max-width: 100%;
            }
            .tour-toolbar-actions {
                flex: 1 1 100%;
                justify-content: stretch;
            }
            .tour-toolbar-actions a {
                flex: 1;
            }
        }
    </style>
    @endsection

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-slate-400 text-[10px] uppercase font-black border-b border-gray-50">
                    <th class="px-6 py-5">Tour</th>
                    <th class="px-6 py-5">Giá cơ bản</th>
                    <th class="px-6 py-5">Thời gian</th>
                    <th class="px-6 py-5">Trạng thái</th>
                    <th class="px-6 py-5 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs font-bold">
                @forelse($tours as $tour)
                <tr class="hover:bg-teal-50/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-14 h-10 rounded-lg bg-slate-100 overflow-hidden shadow-sm flex-shrink-0">
                                @php $primaryImage = $tour->images->where('is_primary', 1)->first() ?? $tour->images->first(); @endphp
                                @if($primaryImage)
                                    <img src="{{ $primaryImage->image_url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                         <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-slate-900 font-black uppercase tracking-tight line-clamp-1">{{ $tour->title }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">ID: #{{ $tour->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-teal-600 font-black">{{ number_format($tour->base_price) }}đ</td>
                    <td class="px-6 py-4 text-slate-500 font-bold italic">{{ $tour->duration }}</td>
                    <td class="px-6 py-4">
                        @if($tour->is_active)
                            <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest">Đang mở</span>
                        @else
                            <span class="px-3 py-1 bg-slate-100 text-slate-400 rounded-full text-[10px] font-black uppercase tracking-widest">Đã đóng</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-1.5">
                            <a href="{{ route('admin.tours.show', $tour) }}" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.tours.edit', $tour) }}?return_url={{ urlencode(request()->fullUrl()) }}" class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(in_array(Auth::user()->role_id, [1, 3]))
                            <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" onsubmit="return confirm('Xác nhận xóa tour này?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-bold italic uppercase text-[10px] tracking-widest">Không tìm thấy bản ghi nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6 border-t border-gray-50 bg-gray-50/30">
        {{ $tours->appends(request()->query())->links() }}
    </div>
</div>
@endsection
