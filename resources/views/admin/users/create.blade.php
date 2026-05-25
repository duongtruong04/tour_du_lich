@extends('layouts.admin')

@section('title', isset($user) ? 'Cập nhật người dùng' : 'Thêm người dùng mới')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card p-0 overflow-hidden shadow-2xl">
        <div class="p-8 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h4 class="text-lg font-black text-slate-800 tracking-tighter uppercase">{{ isset($user) ? 'Chỉnh sửa tài khoản' : 'Đăng ký thành viên mới' }}</h4>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Thông tin chi tiết và quyền truy cập hệ thống</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline !text-[9px] !py-3 rounded-xl border-slate-200 text-slate-400 hover:text-dark">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>

        <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-10">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Avatar Upload -->
                <div class="md:col-span-2 flex flex-col items-center justify-center p-12 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[3rem] group hover:border-primary transition-all relative overflow-hidden">
                    <div class="text-center relative z-10" x-data="{ filename: '' }">
                        <div class="w-32 h-32 bg-white rounded-[2.5rem] flex items-center justify-center text-primary shadow-2xl shadow-primary/10 border-4 border-white mx-auto mb-6 group-hover:scale-110 transition-transform overflow-hidden">
                            @if(isset($user) && $user->avatar)
                                <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-cloud-upload-alt text-4xl opacity-50"></i>
                            @endif
                        </div>
                        <h5 class="text-[10px] font-black uppercase tracking-widest text-slate-800 mb-2">Tải ảnh đại diện</h5>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-6">Định dạng JPG, PNG tối đa 2MB</p>
                        <input type="file" name="avatar" class="hidden" id="avatar" @change="filename = $event.target.files[0].name">
                        <label for="avatar" class="btn btn-primary !text-[9px] !py-3 rounded-xl cursor-pointer">Chọn tệp tin</label>
                        <p x-show="filename" x-text="filename" class="mt-4 text-[10px] font-bold text-primary"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Họ và tên <span class="text-rose-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name ?? '') }}" required placeholder="Ví dụ: Nguyễn Văn A" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Email đăng nhập <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required placeholder="example@email.com" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="0123 456 789" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Vai trò <span class="text-rose-500">*</span></label>
                    <select name="role_id" required class="form-control px-6">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ (old('role_id', $user->role_id ?? '') == $role->id) ? 'selected' : '' }}>{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu {{ isset($user) ? '(Để trống nếu không đổi)' : '*' }}</label>
                    <input type="password" name="password" {{ isset($user) ? '' : 'required' }} placeholder="Tối thiểu 6 ký tự" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Trạng thái hoạt động</label>
                    <div class="flex items-center gap-4 py-3">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status" value="1" {{ old('status', $user->status ?? 1) == 1 ? 'checked' : '' }} class="hidden peer">
                            <div class="w-5 h-5 border-2 border-slate-200 rounded-lg flex items-center justify-center peer-checked:border-primary peer-checked:bg-primary transition-all">
                                <i class="fas fa-check text-[10px] text-white hidden peer-checked:block"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-500 group-hover:text-primary transition-colors">Hoạt động</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group ml-4">
                            <input type="radio" name="status" value="0" {{ old('status', $user->status ?? 1) == 0 ? 'checked' : '' }} class="hidden peer">
                            <div class="w-5 h-5 border-2 border-slate-200 rounded-lg flex items-center justify-center peer-checked:border-rose-500 peer-checked:bg-rose-500 transition-all">
                                <i class="fas fa-check text-[10px] text-white hidden peer-checked:block"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-500 group-hover:text-rose-500 transition-colors">Bị khóa</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-10 border-t border-slate-100 flex gap-4">
                <button type="submit" class="btn btn-primary flex-1 py-5 rounded-2xl !text-[10px] group shadow-xl shadow-teal-500/30">
                    {{ isset($user) ? 'CẬP NHẬT THÔNG TIN' : 'TẠO TÀI KHOẢN MỚI' }} <i class="fas fa-save ml-3 group-hover:translate-y-[-2px] transition-transform"></i>
                </button>
                <button type="reset" class="btn btn-outline border-slate-200 text-slate-400 hover:text-dark px-10 rounded-2xl !text-[10px] py-5">
                    Hủy bỏ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
