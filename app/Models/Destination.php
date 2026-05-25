<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'description', 'image_path'];

    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_destinations');
    }

    public function getImageUrlAttribute()
    {
        $path = $this->image_path;
        $fallback = 'https://placehold.co/600x400/e2e8f0/64748b?text=No+Image';
        
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

        if (\Illuminate\Support\Str::startsWith($path, ['uploads/', '/uploads/'])) {
            $publicPath = public_path(ltrim($path, '/'));
            if (!file_exists($publicPath)) {
                return $fallback;
            }
            return asset(ltrim($path, '/'));
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists(ltrim($path, '/'))) {
            return $fallback;
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
