<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChatBotMessage extends Model
{
    use HasFactory;

    protected $fillable = ['sender_chat_token', 'message', 'message_id', 'group_id'];
}
