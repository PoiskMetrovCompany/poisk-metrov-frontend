<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['chat_token', 'manager_telegram_id', 'manager_id'];

    public function getUser(): User|null
    {
        return User::where('chat_token', $this->chat_token)->first();
    }

    public function clientMessages(): HasMany
    {
        return $this->hasMany(UserChatMessage::class);
    }

    public function managerMessages(): HasMany
    {
        return $this->hasMany(ManagerChatMessage::class);
    }
}
