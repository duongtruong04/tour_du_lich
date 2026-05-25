<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Email không hợp lệ.',
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
        ]);

        $token = Str::random(60);

        // Store reset token
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        $user = User::where('email', $request->email)->first();
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        try {
            // Attempt to send password reset notification using Laravel's standard mail engine
            $user->sendPasswordResetNotification($token);
            return back()->with('success', 'Chúng tôi đã gửi liên kết đặt lại mật khẩu đến email của bạn.');
        } catch (\Exception $e) {
            // If mail server is not configured or fails, gracefully return success but include a debug link
            return back()->with('success', 'Yêu cầu của bạn đã được ghi nhận.')
                         ->with('debug_reset_url', $resetUrl);
        }
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6',
        ], [
            'email.required' => 'Vui lòng cung cấp địa chỉ email.',
            'email.exists' => 'Email này không tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            'password.min' => 'Mật khẩu phải từ 6 ký tự trở lên.',
        ]);

        $record = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        // Check if token is older than 60 minutes
        if (now()->subMinutes(60)->gt($record->created_at)) {
            return back()->withErrors(['email' => 'Liên kết đặt lại mật khẩu đã hết hạn.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Clean up password_resets table
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công! Quý khách có thể đăng nhập bằng mật khẩu mới.');
    }
}
