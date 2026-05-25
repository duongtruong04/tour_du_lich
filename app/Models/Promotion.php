<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'title', 'description', 'discount_value', 'discount_type', 'expiry_date', 'usage_limit', 'used_count', 'image_path'
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
    ];
}
