@extends('layouts.admin')

@section('title', 'Tạo mới Tour')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Tạo mới <span class="text-teal-600">Tour</span></h1>
        <a href="{{ route('admin.tours.index') }}" class="text-slate-400 hover:text-teal-600 text-xs font-black uppercase tracking-widest transition-colors flex items-center">
            <i class="fas fa-arrow-left mr-2 font-black"></i> Quay lại danh sách
        </a>
    </div>

    <form action="{{ route('admin.tours.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Basic Info -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-6">
                    <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        Thông tin cơ bản
                    </h2>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Tên Tour du lịch <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required value="{{ old('title') }}"
                            placeholder="VD: Tour Du Lịch Đà Lạt - Thành Phố Ngàn Hoa"
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700 placeholder-slate-300">
                        @error('title')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1">Giá cơ bản (VNĐ) <span class="text-rose-500">*</span></label>
                            <input type="number" name="base_price" required value="{{ old('base_price') }}"
                                placeholder="5000000" min="1" step="1"
                                class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700 placeholder-slate-300">
                            @error('base_price')
                                <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1">Thời gian diễn ra <span class="text-rose-500">*</span></label>
                            <input type="text" name="duration" required value="{{ old('duration') }}" placeholder="VD: 3 ngày 2 đêm"
                                class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700 placeholder-slate-300">
                            @error('duration')
                                <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Phương tiện di chuyển <span class="text-rose-500">*</span></label>
                        <select name="transportation" required
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700">
                            <option value="">-- Chọn phương tiện --</option>
                            <option value="Máy bay" {{ old('transportation') == 'Máy bay' ? 'selected' : '' }}>✈️ Máy bay</option>
                            <option value="Du thuyền" {{ old('transportation') == 'Du thuyền' ? 'selected' : '' }}>🚢 Du thuyền</option>
                            <option value="Xe du lịch chất lượng cao" {{ old('transportation') == 'Xe du lịch chất lượng cao' ? 'selected' : '' }}>🚌 Xe du lịch chất lượng cao</option>
                        </select>
                        @error('transportation')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Mô tả tóm tắt <span class="text-rose-500">*</span></label>
                        <textarea name="summary" rows="3"
                            placeholder="Nhập mô tả ngắn gọn về hành trình tour để hiển thị ở danh sách ngoài trang chủ..."
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700 placeholder-slate-300">{{ old('summary') }}</textarea>
                        @error('summary')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Bản đồ (Iframe Google Map)</label>
                        <textarea name="google_map" rows="3"
                            placeholder='VD: <iframe src="https://www.google.com/maps/embed?pb=..."></iframe>'
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700 placeholder-slate-300">{{ old('google_map') }}</textarea>
                        @error('google_map')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Lịch trình chi tiết <span class="text-rose-500">*</span></label>
                        <textarea name="itinerary" class="editor" placeholder="Nhập lịch trình chi tiết từng ngày...">{{ old('itinerary') }}</textarea>
                        @error('itinerary')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1 text-emerald-600">Dịch vụ bao gồm <span class="text-rose-500">*</span></label>
                            <textarea name="service_includes" class="editor" placeholder="VD: Vé máy bay, Khách sạn 4 sao, Ăn uống...">{{ old('service_includes') }}</textarea>
                            @error('service_includes')
                                <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1 text-rose-600">Dịch vụ không bao gồm <span class="text-rose-500">*</span></label>
                            <textarea name="service_excludes" class="editor" placeholder="VD: VAT, Chi phí cá nhân, Tip HDV...">{{ old('service_excludes') }}</textarea>
                            @error('service_excludes')
                                <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Multiple Image Upload -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-images"></i>
                        </span>
                        Thư viện Hình ảnh
                    </h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="image-gallery">
                        <label class="aspect-square rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center cursor-pointer hover:bg-teal-50 hover:border-teal-400 transition-all group">
                            <i class="fas fa-plus text-2xl text-slate-300 group-hover:text-teal-400 mb-2 transition-colors"></i>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Thêm ảnh</span>
                            <input type="file" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                        </label>
                    </div>
                    <p class="mt-4 text-[10px] text-slate-400 font-bold italic leading-relaxed">Chọn nhiều ảnh cùng lúc. Ảnh đầu tiên sẽ được đặt làm đại diện mặc định.</p>
                </div>
            </div>

            <!-- Right Side: Destinations & Settings -->
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        Điểm đến đi qua
                    </h2>
                    <div class="space-y-3 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($destinations as $dest)
                        <label class="flex items-center group cursor-pointer p-2 hover:bg-teal-50 rounded-xl transition-colors">
                            <input type="checkbox" name="destinations[]" value="{{ $dest->id }}" class="w-5 h-5 rounded-lg border-slate-200 text-teal-600 focus:ring-teal-500 cursor-pointer">
                            <span class="ml-3 text-sm font-bold text-slate-700 tracking-tight">{{ $dest->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-toggle-on"></i>
                        </span>
                        Trạng thái hiển thị
                    </h2>
                    <select name="is_active" class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700">
                        <option value="1">Hiển thị website</option>
                        <option value="0">Tạm ẩn</option>
                    </select>
                </div>

                <!-- Departures Section -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        Ngày khởi hành
                    </h2>
                    <div id="departures-container" class="space-y-4">
                        <!-- Departure rows will be added here -->
                    </div>
                    <button type="button" onclick="addDepartureRow()" class="mt-4 w-full py-3 bg-slate-50 text-teal-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-teal-50 transition-all border border-dashed border-slate-200 hover:border-teal-300">
                        <i class="fas fa-plus mr-2"></i> Thêm ngày khởi hành
                    </button>
                    <p class="mt-3 text-[10px] text-slate-400 font-bold italic leading-relaxed">Mỗi tour cần ít nhất 1 ngày khởi hành để người dùng có thể đặt tour.</p>
                </div>

                <button type="submit" class="w-full py-5 bg-teal-600 hover:bg-teal-700 text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-teal-500/20 transition-all transform hover:-translate-y-1 active:scale-95">
                    Lưu Tour du lịch
                </button>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    // CKEditor 5 Initializer
    document.querySelectorAll('.editor').forEach(el => {
        ClassicEditor
            .create(el, {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo' ],
                language: 'vi'
            })
            .catch(error => { console.error(error); });
    });

    function previewImages(input) {
        const gallery = document.getElementById('image-gallery');
        const labels = gallery.querySelectorAll('label');
        const lastLabel = labels[labels.length - 1];
        
        gallery.innerHTML = '';
        gallery.appendChild(lastLabel);

        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'aspect-square rounded-3xl bg-slate-50 overflow-hidden shadow-sm relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-teal-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <i class="fas fa-check text-white text-xl"></i>
                        </div>
                    `;
                    gallery.prepend(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    let departureIndex = 0;
    function addDepartureRow() {
        const container = document.getElementById('departures-container');
        const row = document.createElement('div');
        row.className = 'p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-3';
        row.id = 'departure-row-' + departureIndex;
        row.innerHTML = `
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black text-teal-600 uppercase tracking-widest">Ngày khởi hành #${departureIndex + 1}</span>
                <button type="button" onclick="removeDepartureRow('departure-row-${departureIndex}')" class="text-rose-400 hover:text-rose-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1 ml-1">Ngày khởi hành <span class="text-rose-500">*</span></label>
                    <input type="date" name="departures[${departureIndex}][start_date]" required class="w-full px-4 py-2.5 bg-white border-0 rounded-xl focus:ring-2 focus:ring-teal-500 text-sm font-bold text-slate-700">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1 ml-1">Số chỗ tối đa</label>
                    <input type="number" name="departures[${departureIndex}][max_seats]" value="30" min="1" class="w-full px-4 py-2.5 bg-white border-0 rounded-xl focus:ring-2 focus:ring-teal-500 text-sm font-bold text-slate-700">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1 ml-1">Giá riêng (để trống = giá cơ bản)</label>
                    <input type="number" name="departures[${departureIndex}][price_override]" placeholder="Giá riêng nếu có" class="w-full px-4 py-2.5 bg-white border-0 rounded-xl focus:ring-2 focus:ring-teal-500 text-sm font-bold text-slate-700 placeholder-slate-300">
                </div>
            </div>
        `;
        container.appendChild(row);
        departureIndex++;
    }

    function removeDepartureRow(id) {
        document.getElementById(id)?.remove();
    }

    // Add 1 departure row by default
    addDepartureRow();
</script>
@endsection

@section('styles')
<style>
    .ck-editor__editable_inline {
        min-height: 200px;
        border-radius: 0 0 1.5rem 1.5rem !important;
        padding: 1rem 1.5rem !important;
        background-color: #f8fafc !important;
        border: none !important;
    }
    .ck-toolbar {
        border-radius: 1.5rem 1.5rem 0 0 !important;
        background-color: #fff !important;
        border: 1px solid #f1f5f9 !important;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endsection
@endsection
