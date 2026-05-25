@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="card p-0 overflow-hidden">
    <!-- Header/Filter -->
    <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h4 class="text-lg font-black text-slate-800 tracking-tighter uppercase">Danh sách thành viên</h4>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Quản lý tài khoản và phân quyền hệ thống</p>
        </div>
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <div class="flex-1 md:w-64 relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên/email..." class="w-full bg-slate-50 border-none rounded-xl py-2.5 pl-11 pr-4 text-xs font-bold focus:ring-1 focus:ring-primary transition-all">
            </div>
            <select name="role_id" class="bg-slate-50 border-none rounded-xl px-4 py-2.5 text-xs font-bold text-slate-600 focus:ring-1 focus:ring-primary transition-all">
                <option value="">Tất cả vai trò</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary !p-3 rounded-xl shadow-none hover:translate-y-0 active:scale-95"><i class="fas fa-filter"></i></button>
            @if(request()->anyFilled(['search', 'role_id']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline !p-3 rounded-xl border-none bg-slate-100 text-slate-400 hover:bg-slate-200"><i class="fas fa-sync-alt"></i></a>
            @endif
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary !text-[10px] !py-3 rounded-xl whitespace-nowrap ml-2">
                <i class="fas fa-plus mr-2"></i> Thêm mới
            </a>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Hành viên</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Vai trò</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Số điện thoại</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Trạng thái</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50/30 transition-colors group">
                    <td class="p-5">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img src="{{ $user->avatar_url }}" 
                                     class="w-12 h-12 rounded-2xl object-cover shadow-sm border-2 border-white">
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white {{ $user->status ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 leading-none group-hover:text-primary transition-colors">{{ $user->full_name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 mt-1">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-5 text-center">
                        <span class="px-3 py-1.5 rounded-lg border text-[9px] font-black uppercase tracking-widest {{ $user->role->role_name == 'Admin' ? 'bg-indigo-50 text-indigo-600 border-indigo-100 shadow-sm' : 'bg-teal-50 text-teal-600 border-teal-100' }}">
                            {{ $user->role->role_name }}
                        </span>
                    </td>
                    <td class="p-5">
                        <p class="text-[11px] font-black text-slate-700 tracking-tight">{{ $user->phone ?? '---' }}</p>
                    </td>
                    <td class="p-5 text-center">
                        @if($user->status)
                            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest"><i class="fas fa-check-circle mr-1"></i> Hoạt động</span>
                        @else
                            <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest"><i class="fas fa-lock mr-1"></i> Bị khóa</span>
                        @endif
                    </td>
                    <td class="p-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-500 hover:bg-primary hover:text-white transition-all shadow-sm">
                                <i class="fas fa-edit text-[10px]"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Xác nhận xóa người dùng này?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="p-8 border-t border-slate-50">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
