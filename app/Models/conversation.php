<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';

    public $timestamps = false; // 👈 Desativa os campos created_at e updated_at

    protected $fillable = [
        'usuario_id',
        'user_message',
        'ai_response'
    ];
}



