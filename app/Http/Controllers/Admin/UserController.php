<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role_id) {
            $query->where('role_id', $request->role_id);
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['password', 'avatar']);
        $data['password'] = Hash::make($request->password);
        $data['status'] = $request->status ?? 1;

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Tạo người dùng mới thành công.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'phone' => 'nullable|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['password', 'avatar']);
        
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['status'] = $request->status ?? 1;
        $user->update($data);

        $returnUrl = $request->input('return_url');
        if ($returnUrl && str_starts_with($returnUrl, url('/'))) {
            return redirect()->to($returnUrl)->with('success', 'Cập nhật người dùng thành công.');
        }

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công.');
    }

    public function destroy(User $user)
    {
        // Block logged in user from deleting themselves
        if (auth()->id() == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể tự xóa tài khoản của chính mình.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            // Delete reviews
            $user->reviews()->delete();

            // Delete chat history
            \Illuminate\Support\Facades\DB::table('chat_history')->where('user_id', $user->id)->delete();

            // Delete bookings and their relations (restoring seats if active)
            foreach ($user->bookings as $booking) {
                if ($booking->status != 'Cancelled' && $booking->departure) {
                    $booking->departure->increment('available_seats', $booking->passengers()->count());
                }
                $booking->payments()->delete();
                $booking->passengers()->delete();
                $booking->delete();
            }

            // Delete avatar
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }

            // Delete user
            $user->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công.');
    }
}
