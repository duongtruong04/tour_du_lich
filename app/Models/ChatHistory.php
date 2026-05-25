<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    use HasFactory;

    protected $table = 'chat_history';

    protected $fillable = ['user_id', 'session_id', 'message', 'reply'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
