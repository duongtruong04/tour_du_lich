<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatHistory;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatHistory::with('user');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('message', 'like', '%' . $request->search . '%')
                  ->orWhere('reply', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $chat_histories = $query->latest()->paginate(20);

        return view('admin.chats.index', compact('chat_histories'));
    }

    public function show(ChatHistory $chat)
    {
        return view('admin.chats.show', compact('chat'));
    }

    public function destroy(ChatHistory $chat)
    {
        $chat->delete();
        return redirect()->route('admin.chats.index')->with('success', 'Xóa lịch sử chat thành công.');
    }
}
