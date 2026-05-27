@extends('layouts.admin')

@section('title', 'Đăng bài viết mới')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Đăng bài <span class="text-teal-600">Tin tức</span></h1>
        <a href="{{ route('admin.news.index') }}" class="text-slate-400 hover:text-teal-600 text-xs font-black uppercase tracking-widest transition-colors flex items-center">
            <i class="fas fa-arrow-left mr-2 font-black"></i> Quay lại danh sách
        </a>
    </div>

    <form id="news-form" action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Content -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Tiêu đề bài viết <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required value="{{ old('title') }}"
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700 placeholder-slate-300">
                        @error('title')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Nội dung chi tiết <span class="text-rose-500">*</span></label>
                        <textarea name="content" id="news-content" rows="12"
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700 placeholder-slate-300">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 ml-1">Mô tả tóm tắt (SEO)</label>
                        <textarea name="summary" rows="3"
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700 placeholder-slate-300">{{ old('summary') }}</textarea>
                        @error('summary')
                            <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Side: Settings & Media -->
            <div class="space-y-8">
                <!-- Thumbnail -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-image"></i>
                        </span>
                        Ảnh đại diện
                    </h2>
                    <div class="relative group">
                        <div id="image-preview" class="w-full aspect-[16/9] rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden transition-all group-hover:border-teal-400 group-hover:bg-teal-50">
                            <i class="fas fa-image text-4xl text-slate-300 group-hover:text-teal-300 transition-colors"></i>
                        </div>
                        <input type="file" name="image" id="image-input" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                    </div>
                    <p class="mt-4 text-[10px] text-slate-400 font-bold italic leading-relaxed text-center">Định dạng hỗ trợ: JPG, PNG, WEBP. Tối đa 10MB.</p>
                    @error('image')
                        <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-tags"></i>
                        </span>
                        Chuyên mục
                    </h2>
                    <select name="category_id" required class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-5 bg-teal-600 hover:bg-teal-700 text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-teal-500/20 transition-all transform hover:-translate-y-1 active:scale-95">
                        Đăng bài viết
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    // CKEditor 5 Initialization - save instance for form sync
    let newsEditor;
    ClassicEditor
        .create(document.querySelector('#news-content'), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo' ],
            language: 'vi'
        })
        .then(editor => {
            newsEditor = editor;
        })
        .catch(error => {
            console.error(error);
        });

    // Sync CKEditor data back to textarea before form submit
    document.getElementById('news-form').addEventListener('submit', function(e) {
        if (newsEditor) {
            document.querySelector('#news-content').value = newsEditor.getData();
        }
    });

    // Image Preview
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

@section('styles')
<style>
    /* CKEditor Custom Styling */
    .ck-editor__editable_inline {
        min-height: 400px;
        border-radius: 0 0 1.5rem 1.5rem !important;
        padding: 1.5rem 2rem !important;
        background-color: #f8fafc !important;
        border: none !important;
        font-family: inherit;
        font-size: 0.875rem;
    }
    .ck-toolbar {
        border-radius: 1.5rem 1.5rem 0 0 !important;
        background-color: #fff !important;
        border: 1px solid #f1f5f9 !important;
        padding: 0.5rem !important;
    }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        border-color: transparent !important;
    }
    .ck.ck-editor__main>.ck-editor__editable.ck-focused {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
    }
</style>
@endsection
