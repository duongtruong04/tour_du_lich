<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{
    protected $fillable = [
        'user_id',
        'email_hash',
        'code_hash',
        'sent_at',
        'expires_at',
        'attempts',
        'locked_until',
        'verified_at',
        'used_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'sent_at'     => 'datetime',
        'expires_at'  => 'datetime',
        'locked_until' => 'datetime',
        'verified_at' => 'datetime',
        'used_at'     => 'datetime',
    ];

    /**
     * Relationship: OTP thuộc về 1 User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kiểm tra mã đã hết hạn chưa.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Kiểm tra mã đang bị khóa (nhập sai quá nhiều).
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Kiểm tra mã đã được sử dụng chưa.
     */
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * Tăng số lần nhập sai và khóa nếu vượt giới hạn.
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');

        $maxAttempts = config('password_reset.max_attempts', 5);
        $lockMinutes = config('password_reset.lock_minutes', 15);

        if ($this->attempts >= $maxAttempts) {
            $this->update([
                'locked_until' => now()->addMinutes($lockMinutes),
            ]);
        }
    }

    /**
     * Đánh dấu mã đã sử dụng thành công.
     */
    public function markAsUsed(): void
    {
        $this->update([
            'used_at'     => now(),
            'verified_at' => now(),
        ]);
    }

    /**
     * Tạo email_hash từ email bằng HMAC SHA256.
     */
    public static function hashEmail(string $email): string
    {
        $normalizedEmail = strtolower(trim($email));
        return hash_hmac('sha256', $normalizedEmail, config('app.key'));
    }
}
