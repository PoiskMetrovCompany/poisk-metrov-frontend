<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'user_id',
        'apartment_id',
        'key',
        'reservation_key',
    ];
}
