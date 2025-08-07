<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatTokenCRMLeadPair extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_token',
        'crm_city',
        'crm_id'
    ];
}
