<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Code Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình cho chức năng quên mật khẩu bằng OTP qua Resend API.
    | Tất cả giá trị đọc từ .env, không hardcode trong controller.
    |
    */

    // OTP hết hạn sau bao nhiêu phút
    'code_expire_minutes' => env('PASSWORD_RESET_CODE_EXPIRE_MINUTES', 10),

    // Thời gian chờ tối thiểu giữa 2 lần gửi OTP (giây)
    'resend_cooldown_seconds' => env('PASSWORD_RESET_RESEND_COOLDOWN_SECONDS', 60),

    // Số lần gửi OTP tối đa trong 15 phút theo email
    'max_sends_per_15_minutes' => env('PASSWORD_RESET_MAX_SENDS_PER_15_MINUTES', 3),

    // Số lần gửi OTP tối đa trong 15 phút theo IP
    'max_ip_sends_per_15_minutes' => env('PASSWORD_RESET_MAX_IP_SENDS_PER_15_MINUTES', 10),

    // Số lần nhập sai OTP tối đa trước khi khóa
    'max_attempts' => env('PASSWORD_RESET_MAX_ATTEMPTS', 5),

    // Thời gian khóa mã OTP khi nhập sai quá giới hạn (phút)
    'lock_minutes' => env('PASSWORD_RESET_LOCK_MINUTES', 15),

    // Cooldown sau khi reset mật khẩu thành công (phút)
    'after_success_cooldown_minutes' => env('PASSWORD_RESET_AFTER_SUCCESS_COOLDOWN_MINUTES', 15),

];
