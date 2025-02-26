<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessages extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['chat_session_id', 'message', 'sender_id'];

    public function session() 
    {
        return $this->belongsTo(ChatSession::class);
    }
}