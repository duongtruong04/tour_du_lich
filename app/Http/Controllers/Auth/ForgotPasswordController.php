<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Thông báo chung cho tất cả trường hợp gửi OTP.
     * Không tiết lộ email có tồn tại hay không.
     */
    private const GENERIC_SEND_MESSAGE = 'Nếu email này đã được đăng ký, chúng tôi đã gửi mã đặt lại mật khẩu đến hộp thư của bạn. Vui lòng kiểm tra hộp thư chính hoặc thư rác. Mã có hiệu lực trong 10 phút.';

    /**
     * Thông báo chung khi OTP không hợp lệ.
     * Không tiết lộ email có tồn tại hay không.
     */
    private const GENERIC_INVALID_CODE_MESSAGE = 'Mã xác thực không hợp lệ hoặc đã hết hạn.';

    // ─── FORM HIỂN THỊ ────────────────────────────────────────────────

    /**
     * Hiển thị form nhập email quên mật khẩu.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Hiển thị form nhập OTP + mật khẩu mới.
     */
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'email' => $request->query('email', old('email', session('reset_email', ''))),
        ]);
    }

    // ─── GỬI MÃ OTP ──────────────────────────────────────────────────

    /**
     * Xử lý gửi mã OTP đặt lại mật khẩu.
     *
     * Bảo mật:
     * - Không dùng exists:users,email → không lộ email tồn tại
     * - Rate limit theo email + IP
     * - Cooldown giữa các lần gửi
     * - Cooldown sau reset thành công
     * - Delay ngẫu nhiên khi email không tồn tại (chống timing attack)
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email'    => 'Địa chỉ email không hợp lệ.',
            'email.max'      => 'Địa chỉ email quá dài.',
        ]);

        $email = strtolower(trim($request->email));
        $emailHash = PasswordResetCode::hashEmail($email);
        $ip = $request->ip();

        // Mask email cho log (không log full email)
        $emailMasked = substr($email, 0, 3) . '***@' . substr(strrchr($email, '@'), 1);

        // ── Rate limit: IP (max 10 lần / 15 phút) ──
        if ($this->isIpRateLimited($ip)) {
            Log::info('Password reset blocked: IP rate limit', ['ip' => $ip]);
            return $this->redirectWithGenericMessage($request, $email);
        }

        // ── Rate limit: Email (max 3 lần / 15 phút) ──
        if ($this->isEmailRateLimited($emailHash)) {
            Log::info('Password reset blocked: email rate limit', ['email_masked' => $emailMasked]);
            return $this->redirectWithGenericMessage($request, $email);
        }

        // ── Cooldown: Phải chờ 60 giây giữa các lần gửi ──
        if ($this->isResendCooldownActive($emailHash)) {
            Log::info('Password reset blocked: resend cooldown active', ['email_masked' => $emailMasked]);
            return $this->redirectWithGenericMessage($request, $email);
        }

        // ── Cooldown: Sau reset thành công 15 phút ──
        if ($this->isPostResetCooldownActive($emailHash)) {
            Log::info('Password reset blocked: post-reset cooldown active', ['email_masked' => $emailMasked]);
            return $this->redirectWithGenericMessage($request, $email);
        }

        // ── Tìm user ──
        $user = User::where('email', $email)->first();

        if ($user) {
            // Vô hiệu hóa các mã cũ chưa dùng
            PasswordResetCode::where('email_hash', $emailHash)
                ->whereNull('used_at')
                ->update(['used_at' => now()]);

            // Tạo OTP 6 số
            $otpPlain = (string) random_int(100000, 999999);
            $expireMinutes = config('password_reset.code_expire_minutes', 10);

            // Lưu OTP đã hash
            PasswordResetCode::create([
                'user_id'    => $user->id,
                'email_hash' => $emailHash,
                'code_hash'  => Hash::make($otpPlain),
                'sent_at'    => now(),
                'expires_at' => now()->addMinutes($expireMinutes),
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
            ]);

            // Gửi email qua Resend API
            $fromAddress = config('mail.from.address', 'no-reply@tourtravel.site');
            $fromName = config('mail.from.name', 'TourTravel');
            $fromFull = $fromName . ' <' . $fromAddress . '>';

            $emailHtml = view('emails.password-reset-code', [
                'otpCode'       => $otpPlain,
                'userName'      => $user->full_name ?? 'Quý khách',
                'expireMinutes' => $expireMinutes,
            ])->render();

            try {
                $resend = \Resend::client(config('services.resend.key'));
                $result = $resend->emails->send([
                    'from'    => $fromFull,
                    'to'      => [$email],
                    'subject' => 'Mã đặt lại mật khẩu TourTravel',
                    'html'    => $emailHtml,
                ]);

                // Log thành công kèm Resend email ID
                $resendId = $result->id ?? ($result['id'] ?? 'unknown');
                Log::info('Password reset OTP email sent via Resend', [
                    'email_masked'    => $emailMasked,
                    'resend_email_id' => $resendId,
                    'from'            => $fromAddress,
                ]);
            } catch (\Exception $e) {
                Log::warning('Password reset OTP email failed with primary from address', [
                    'email_masked' => $emailMasked,
                    'from'         => $fromAddress,
                    'error'        => $e->getMessage(),
                ]);

                // Fallback: nếu domain chưa verified, dùng onboarding@resend.dev
                if (str_contains($e->getMessage(), 'domain is not verified')) {
                    try {
                        $fallbackFrom = $fromName . ' <onboarding@resend.dev>';
                        $resendFallback = \Resend::client(config('services.resend.key'));
                        $resultFallback = $resendFallback->emails->send([
                            'from'    => $fallbackFrom,
                            'to'      => [$email],
                            'subject' => 'Mã đặt lại mật khẩu TourTravel',
                            'html'    => $emailHtml,
                        ]);

                        $resendId = $resultFallback->id ?? ($resultFallback['id'] ?? 'unknown');
                        Log::info('Password reset OTP email sent via Resend (fallback onboarding@resend.dev)', [
                            'email_masked'    => $emailMasked,
                            'resend_email_id' => $resendId,
                            'from'            => 'onboarding@resend.dev',
                        ]);
                    } catch (\Exception $e2) {
                        Log::error('Password reset OTP email failed (both primary and fallback)', [
                            'email_masked'  => $emailMasked,
                            'primary_from'  => $fromAddress,
                            'fallback_from' => 'onboarding@resend.dev',
                            'primary_error' => $e->getMessage(),
                            'fallback_error'=> $e2->getMessage(),
                            'ip'            => $ip,
                        ]);
                    }
                }
            }
        } else {
            // Email không tồn tại → delay ngẫu nhiên để chống timing attack
            Log::info('Password reset: email not found in users table', ['email_masked' => $emailMasked]);
            usleep(random_int(150000, 350000)); // 150-350ms
        }

        return $this->redirectWithGenericMessage($request, $email);
    }

    // ─── ĐẶT LẠI MẬT KHẨU ───────────────────────────────────────────

    /**
     * Xử lý đặt lại mật khẩu bằng OTP.
     *
     * Bảo mật:
     * - Validate OTP 6 số, password min 8 ký tự, có chữ và số
     * - Kiểm tra OTP hợp lệ, chưa dùng, chưa hết hạn
     * - Kiểm tra locked_until (bị khóa do nhập sai quá nhiều)
     * - Hash::check OTP
     * - Vô hiệu hóa tất cả OTP cũ sau khi reset thành công
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|max:255',
            'code'     => 'required|digits:6',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).+$/',
            ],
        ], [
            'email.required'     => 'Vui lòng nhập địa chỉ email.',
            'email.email'        => 'Địa chỉ email không hợp lệ.',
            'code.required'      => 'Vui lòng nhập mã xác thực.',
            'code.digits'        => 'Mã xác thực phải gồm 6 chữ số.',
            'password.required'  => 'Vui lòng nhập mật khẩu mới.',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.regex'     => 'Mật khẩu phải chứa ít nhất 1 chữ cái và 1 chữ số.',
        ]);

        $email = strtolower(trim($request->email));
        $emailHash = PasswordResetCode::hashEmail($email);

        // Tìm OTP mới nhất: đúng email_hash, chưa dùng, chưa hết hạn
        $resetCode = PasswordResetCode::where('email_hash', $emailHash)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();

        // Không tìm thấy record hợp lệ
        if (!$resetCode) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['code' => self::GENERIC_INVALID_CODE_MESSAGE]);
        }

        // Mã đang bị khóa do nhập sai quá nhiều
        if ($resetCode->isLocked()) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['code' => 'Mã xác thực đã bị khóa do nhập sai quá nhiều lần. Vui lòng yêu cầu gửi mã mới.']);
        }

        // Kiểm tra OTP
        if (!Hash::check($request->code, $resetCode->code_hash)) {
            // Sai → tăng attempts, lock nếu quá giới hạn
            $resetCode->incrementAttempts();

            $maxAttempts = config('password_reset.max_attempts', 5);
            $remaining = $maxAttempts - $resetCode->fresh()->attempts;

            if ($remaining <= 0) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['code' => 'Mã xác thực đã bị khóa do nhập sai quá nhiều lần. Vui lòng yêu cầu gửi mã mới.']);
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['code' => self::GENERIC_INVALID_CODE_MESSAGE]);
        }

        // ── OTP đúng → Đổi mật khẩu ──
        $user = User::where('email', $email)->first();

        // Kiểm tra user và user_id khớp
        if (!$user || ($resetCode->user_id && $resetCode->user_id !== $user->id)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['code' => self::GENERIC_INVALID_CODE_MESSAGE]);
        }

        // Cập nhật mật khẩu
        $user->update([
            'password'       => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ]);

        // Đánh dấu mã đã sử dụng
        $resetCode->markAsUsed();

        // Vô hiệu hóa tất cả OTP cũ cùng user/email
        PasswordResetCode::where('email_hash', $emailHash)
            ->where('id', '!=', $resetCode->id)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        return redirect()->route('login')
            ->with('success', 'Mật khẩu đã được cập nhật thành công. Vui lòng đăng nhập lại.');
    }

    // ─── HELPER: RATE LIMIT ───────────────────────────────────────────

    /**
     * Kiểm tra rate limit theo IP: max N lần trong 15 phút.
     */
    private function isIpRateLimited(string $ip): bool
    {
        $maxPerIp = config('password_reset.max_ip_sends_per_15_minutes', 10);
        $count = PasswordResetCode::where('ip_address', $ip)
            ->where('sent_at', '>=', now()->subMinutes(15))
            ->count();

        return $count >= $maxPerIp;
    }

    /**
     * Kiểm tra rate limit theo email: max N lần trong 15 phút.
     */
    private function isEmailRateLimited(string $emailHash): bool
    {
        $maxPerEmail = config('password_reset.max_sends_per_15_minutes', 3);
        $count = PasswordResetCode::where('email_hash', $emailHash)
            ->where('sent_at', '>=', now()->subMinutes(15))
            ->count();

        return $count >= $maxPerEmail;
    }

    /**
     * Kiểm tra cooldown giữa các lần gửi: phải chờ N giây.
     */
    private function isResendCooldownActive(string $emailHash): bool
    {
        $cooldownSeconds = config('password_reset.resend_cooldown_seconds', 60);
        $lastSent = PasswordResetCode::where('email_hash', $emailHash)
            ->whereNotNull('sent_at')
            ->latest('sent_at')
            ->first();

        if ($lastSent && $lastSent->sent_at->diffInSeconds(now()) < $cooldownSeconds) {
            return true;
        }

        return false;
    }

    /**
     * Kiểm tra cooldown sau khi reset mật khẩu thành công:
     * Không gửi email mới trong N phút.
     */
    private function isPostResetCooldownActive(string $emailHash): bool
    {
        $cooldownMinutes = config('password_reset.after_success_cooldown_minutes', 15);
        $lastSuccess = PasswordResetCode::where('email_hash', $emailHash)
            ->whereNotNull('used_at')
            ->whereNotNull('verified_at')
            ->latest('used_at')
            ->first();

        if ($lastSuccess && $lastSuccess->used_at->diffInMinutes(now()) < $cooldownMinutes) {
            return true;
        }

        return false;
    }

    /**
     * Redirect với thông báo chung + lưu email vào session.
     */
    private function redirectWithGenericMessage(Request $request, string $email)
    {
        // Lưu email vào session để trang reset-password tự điền
        session()->put('reset_email', $email);

        return redirect()->route('password.reset.form')
            ->with('status', self::GENERIC_SEND_MESSAGE);
    }
}
