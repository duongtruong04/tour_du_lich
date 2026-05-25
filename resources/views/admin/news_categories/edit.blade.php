@extends('layouts.admin')

@section('title', 'Chỉnh sửa chuyên mục')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.news_categories.update', $newsCategory->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2 font-bold uppercase text-[10px] tracking-widest text-indigo-500">Tên chuyên mục</label>
                    <input type="text" name="name" value="{{ old('name', $newsCategory->name) }}" required placeholder="VD: Cẩm nang du lịch" 
                        class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                </div>
                
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.news_categories.index') }}" class="px-6 py-3 border rounded-lg hover:bg-gray-50 transition font-bold uppercase text-xs text-gray-400">Hủy</a>
                    <button type="submit" class="px-12 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition shadow-lg shadow-indigo-100 uppercase text-xs tracking-widest">
                        Lưu Thay Đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
