<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code', 'user_id', 'departure_id', 'total_price', 'status', 'payment_status', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departure()
    {
        return $this->belongsTo(Departure::class);
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
