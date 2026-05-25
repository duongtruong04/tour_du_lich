@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="p-4 bg-gray-50 border-b border-gray-100">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative md:col-span-2">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên khách hoặc tên tour..." 
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>
            <div>
                <select name="rating" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                    <option value="">-- Tất cả mức sao --</option>
                    @for($i=5; $i>=1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                    @endfor
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex-1 text-sm font-bold">Lọc</button>
            </div>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                    <th class="px-6 py-4">Khách hàng / Tour</th>
                    <th class="px-6 py-4">Đánh giá</th>
                    <th class="px-6 py-4">Nội dung bình luận</th>
                    <th class="px-6 py-4">Ngày gửi</th>
                    <th class="px-6 py-4 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 transition border-l-4 border-l-transparent hover:border-l-yellow-400">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 text-sm">{{ $review->user->full_name }}</div>
                        <div class="text-[10px] text-indigo-500 uppercase tracking-widest font-bold">{{ $review->tour->title }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex text-yellow-400 text-xs">
                            @for($i=1; $i<=5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600 line-clamp-2 italic leading-relaxed">"{{ $review->comment }}"</div>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-400 font-medium">
                        {{ $review->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 w-8 h-8 rounded-full flex items-center justify-center transition" onclick="return confirm('Xóa đánh giá này?')" title="Xóa">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Chưa có đánh giá nào từ khách hàng.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100 bg-gray-50">
        {{ $reviews->appends(request()->query())->links() }}
    </div>
</div>
@endsection
