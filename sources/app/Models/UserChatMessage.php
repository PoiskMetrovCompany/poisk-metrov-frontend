<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['chat_session_id', 'message', 'chat_token'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }
}
