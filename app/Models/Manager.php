<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'phone',
        'document_name',
        'avatar_file_name',
        'city',
        'crm_id',
        'user_id',
        'telegram_id'
    ];
}
