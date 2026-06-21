<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role_id',
        'phone',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role_id' => 'integer',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin()
    {
        return $this->role_id === 1; // 'Admin' role_id in SQL is 1
    }

    public function isEmployee()
    {
        return $this->role_id === 3; // 'Employee' role_id in SQL is 3
    }

    public function isStaff()
    {
        return $this->role_id === 3; // 'Employee/Staff' role_id in SQL is 3
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAvatarUrlAttribute()
    {
        $path = $this->avatar;
        $fallback = 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name ?? 'User') . '&background=0D9488&color=fff&size=128';

        if (!$path) {
            return $fallback;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['/storage/'])) {
            $relativePath = str_replace('/storage/', '', $path);
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                return $fallback;
            }
            return asset(ltrim($path, '/'));
        }

        if (\Illuminate\Support\Str::startsWith($path, ['storage/'])) {
            $relativePath = str_replace('storage/', '', $path);
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                return $fallback;
            }
            return asset($path);
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists(ltrim($path, '/'))) {
            return $fallback;
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
