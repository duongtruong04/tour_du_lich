@extends('layouts.admin')

@section('title', 'Hỗ trợ khách hàng (Chat)')

@section('content')
<div class="card p-0 overflow-hidden">
    <!-- Header/Filter -->
    <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h4 class="text-lg font-black text-slate-800 tracking-tighter uppercase">Lịch sử hội thoại</h4>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Theo dõi tin nhắn và tương tác cùng trí tuệ nhân tạo</p>
        </div>
        <form action="{{ route('admin.chats.index') }}" method="GET" class="flex flex-wrap items-center gap-4 w-full md:w-auto">
            <div class="flex-1 md:w-64 relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nội dung tin nhắn..." class="w-full bg-slate-50 border-none rounded-xl py-2.5 pl-11 pr-4 text-xs font-bold focus:ring-1 focus:ring-primary transition-all">
            </div>
            <button type="submit" class="btn btn-primary !p-3 rounded-xl"><i class="fas fa-filter"></i></button>
        </form>
    </div>

    <!-- Conversations -->
    <div class="p-8 space-y-6">
        @forelse($chat_histories as $chat)
        <div class="p-8 bg-slate-50 border border-slate-100 rounded-[2.5rem] group hover:shadow-xl transition-all duration-500 relative">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- User Column -->
                <div class="md:w-48 border-r border-slate-200 pr-8">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ $chat->user ? $chat->user->avatar_url : 'https://ui-avatars.com/api/?name=Guest&background=E2E8F0&color=64748B' }}" 
                             class="w-10 h-10 rounded-xl object-cover shadow-sm">
                        <div>
                            <p class="text-[11px] font-black text-slate-800 leading-none">{{ $chat->user->full_name ?? 'Khách vãng lai' }}</p>
                            <p class="text-[9px] font-black text-primary mt-1 uppercase tracking-widest">{{ $chat->user ? 'Member' : 'Guest' }}</p>
                        </div>
                    </div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest"><i class="far fa-clock mr-1"></i> {{ $chat->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <!-- Message Column -->
                <div class="flex-1 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-50 relative group/msg">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Người dùng:</p>
                        <p class="text-sm font-medium text-slate-700 italic leading-relaxed">{{ $chat->message }}</p>
                    </div>

                    <div class="bg-primary/5 p-6 rounded-2xl border border-primary/10 relative">
                        <p class="text-[9px] font-black text-primary uppercase tracking-[0.2em] mb-3">Hệ thống phản hồi:</p>
                        <p class="text-sm font-medium text-slate-700 leading-relaxed">{{ $chat->reply ?? 'Đang chờ xử lý...' }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="md:w-12 flex items-start justify-end">
                    <form action="{{ route('admin.chats.destroy', $chat) }}" method="POST" onsubmit="return confirm('Xóa lịch sử chat này?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-slate-300 hover:bg-rose-500 hover:text-white transition-all shadow-sm group-hover:bg-slate-50 group-hover:text-slate-500">
                            <i class="fas fa-trash-alt text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-20 text-center border-2 border-dashed border-slate-100 rounded-[3rem]">
            <i class="fas fa-comments text-4xl text-slate-200 mb-4 block"></i>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Không có hội thoại nào được ghi lại</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($chat_histories->hasPages())
    <div class="p-8 border-t border-slate-50">
        {{ $chat_histories->links() }}
    </div>
    @endif
</div>
@endsection
