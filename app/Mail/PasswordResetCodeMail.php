<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otpCode;
    public string $userName;
    public int $expireMinutes;

    /**
     * Create a new message instance.
     *
     * @param string $otpCode      Mã OTP 6 số (plain text, chỉ dùng để hiển thị trong email)
     * @param string $userName     Tên người dùng
     * @param int    $expireMinutes Thời gian hết hạn OTP (phút)
     */
    public function __construct(string $otpCode, string $userName, int $expireMinutes)
    {
        $this->otpCode = $otpCode;
        $this->userName = $userName;
        $this->expireMinutes = $expireMinutes;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Mã đặt lại mật khẩu TourTravel')
                    ->view('emails.password-reset-code')
                    ->with([
                        'otpCode'       => $this->otpCode,
                        'userName'      => $this->userName,
                        'expireMinutes' => $this->expireMinutes,
                    ]);
    }
}
