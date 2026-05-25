@extends('layouts.admin')

@section('title', 'Cập nhật khuyến mãi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Cập nhật <span class="text-teal-600">Khuyến mãi</span></h1>
        <a href="{{ route('admin.promotions.index') }}" class="text-slate-400 hover:text-teal-600 text-xs font-black uppercase tracking-widest transition-colors flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
        <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Mã CODE <span class="text-rose-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $promotion->code) }}" required 
                            placeholder="VD: MAGIAMGIA2026"
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-mono tracking-widest uppercase font-bold text-slate-700 placeholder-slate-300">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Tiêu đề biểu ngữ (Banner Title)</label>
                        <input type="text" name="title" value="{{ old('title', $promotion->title) }}" placeholder="VD: Ưu đãi mùa hè rực rỡ" 
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Mô tả chương trình (Banner Description)</label>
                    <textarea name="description" rows="3" placeholder="Nhập mô tả ngắn cho banner ưu đãi..." 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700">{{ old('description', $promotion->description) }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Hình ảnh biểu ngữ (Banner Image)</label>
                    @if($promotion->image_path)
                        <div class="mb-4 relative w-full h-48 rounded-2xl overflow-hidden border border-slate-100">
                            <img src="{{ asset('storage/' . $promotion->image_path) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-dark/20 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                <span class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-800 shadow-xl">Ảnh hiện tại</span>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Loại giảm giá</label>
                        <select name="discount_type" required class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700">
                            <option value="Percentage" {{ $promotion->discount_type == 'Percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                            <option value="Fixed" {{ $promotion->discount_type == 'Fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Giá trị giảm</label>
                        <input type="number" name="discount_value" value="{{ old('discount_value', (int)$promotion->discount_value) }}" required placeholder="10 hoặc 100000" 
                            min="1" step="1"
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700">
                        @error('discount_value')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Hạn sử dụng</label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date', $promotion->expiry_date ? $promotion->expiry_date->format('Y-m-d') : '') }}" required 
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700">
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Giới hạn sử dụng (Lượt) <span class="text-rose-500">*</span></label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit', $promotion->usage_limit) }}" required
                        placeholder="VD: 50, 100..." min="1" step="1"
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700 placeholder-slate-300">
                    @error('usage_limit')
                        <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t mt-8">
                    <a href="{{ route('admin.promotions.index') }}" class="px-6 py-3 border rounded-lg hover:bg-gray-50 transition font-bold uppercase text-xs text-gray-400">Hủy</a>
                    <button type="submit" class="px-12 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition shadow-lg shadow-indigo-100 uppercase text-xs tracking-widest">
                        Lưu Thay Đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
