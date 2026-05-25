<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'summary', 'google_map', 'itinerary', 'base_price', 'duration', 'transportation', 'service_includes', 'service_excludes', 'is_active'
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Boot method - auto-generate slug if empty.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tour) {
            if (empty($tour->slug) && !empty($tour->title)) {
                $tour->slug = \Illuminate\Support\Str::slug($tour->title);
            }
        });
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'tour_destinations');
    }

    public function departures()
    {
        return $this->hasMany(Departure::class);
    }

    public function images()
    {
        return $this->hasMany(TourImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Departure::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
