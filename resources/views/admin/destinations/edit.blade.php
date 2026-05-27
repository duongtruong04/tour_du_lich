@extends('layouts.admin')

@section('title', 'Chỉnh sửa Điểm đến')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Chỉnh sửa <span class="text-teal-600">Điểm đến</span></h1>
        <a href="{{ request('return_url', route('admin.destinations.index')) }}" class="text-slate-400 hover:text-teal-600 text-xs font-black uppercase tracking-widest transition-colors flex items-center">
            <i class="fas fa-arrow-left mr-2 font-black"></i> Quay lại danh sách
        </a>
    </div>

    <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        <input type="hidden" name="return_url" value="{{ request('return_url', route('admin.destinations.index')) }}">
        
        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col md:flex-row gap-10">
            <!-- Image Upload Column -->
            <div class="w-full md:w-1/3 text-center">
                <label class="block text-[10px] font-black text-teal-900 uppercase tracking-widest mb-4 ml-1">Hình ảnh hiện tại</label>
                <div class="relative group">
                    <div id="image-preview" class="w-full aspect-[4/3] rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden transition-all group-hover:border-teal-400 group-hover:bg-teal-50">
                        @if($destination->image_path)
                            <img src="{{ $destination->image_url }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-image text-4xl text-slate-300 group-hover:text-teal-300 transition-colors"></i>
                        @endif
                    </div>
                    <input type="file" name="image" id="image-input" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                </div>
                <p class="mt-4 text-[10px] text-slate-400 font-bold italic leading-relaxed">Nhấn vào ảnh để thay đổi mới.</p>
            </div>

            <!-- Content Column -->
            <div class="flex-1 space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Tên Điểm đến <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name', $destination->name) }}"
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700 placeholder-slate-300"
                        placeholder="VD: Vịnh Hạ Long">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Khu vực / Tỉnh thành <span class="text-rose-500">*</span></label>
                    <input type="text" name="location" required value="{{ old('location', $destination->location) }}"
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700 placeholder-slate-300"
                        placeholder="VD: Quảng Ninh">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Mô tả tóm tắt</label>
                    <textarea name="description" rows="5"
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700 placeholder-slate-300">{{ old('description', $destination->description) }}</textarea>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" class="flex-1 py-5 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-teal-500/20 transition-all transform hover:-translate-y-1 active:scale-95">
                        Cập nhật thông tin
                    </button>
                    <a href="{{ request('return_url', route('admin.destinations.index')) }}" class="px-10 py-5 bg-slate-100 text-slate-400 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all text-center">Hủy</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('image-input').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
