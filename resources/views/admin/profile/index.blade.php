@extends('layouts.admin')

@section('title', 'Thông Tin Quản Trị Viên')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header Banner -->
    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200 flex flex-col sm:flex-row items-center gap-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative">
            <img src="{{ $user->avatar_url }}" 
                 class="w-24 h-24 rounded-2xl object-cover shadow-lg shadow-teal-500/20 border-4 border-white">
            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-primary text-white rounded-xl flex items-center justify-center text-xs shadow-md">
                <i class="fas fa-shield-alt"></i>
            </div>
        </div>
        <div class="text-center sm:text-left">
            <div class="flex items-center justify-center sm:justify-start gap-3 mb-1">
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">{{ $user->full_name }}</h2>
                <span class="px-3 py-1 bg-primary/10 text-primary rounded-lg text-[10px] font-black uppercase tracking-widest">Admin</span>
            </div>
            <p class="text-xs text-slate-500 font-bold mb-3">{{ $user->email }}</p>
            <p class="text-xs text-slate-400 italic">Quản trị viên cấp cao hệ thống {{ $settings['site_name'] ?? 'Tour Du Lịch' }}</p>
        </div>
    </div>

    <!-- Forms Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Profile Form -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-edit text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-800 uppercase tracking-tight">Thông Tin Cá Nhân</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Cập nhật tên, số điện thoại và ảnh đại diện</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Họ và tên</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Ảnh đại diện (Avatar)</label>
                    <input type="file" name="avatar" accept="image/*"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 text-xs font-bold text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:transition-all">
                </div>

                <button type="submit" class="btn btn-primary w-full py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-save mr-2"></i> Lưu Thông Tin
                </button>
            </form>
        </div>

        <!-- Password Form -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 bg-amber-500/10 text-amber-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-key text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-800 uppercase tracking-tight">Đổi Mật Khẩu</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Bảo mật tài khoản quản trị</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" required placeholder="••••••••"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Mật khẩu mới</label>
                    <input type="password" name="password" required placeholder="•••••••• (Tối thiểu 8 ký tự)"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                </div>

                <button type="submit" class="btn w-full py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-amber-500/20" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                    <i class="fas fa-lock mr-2"></i> Đổi Mật Khẩu
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
