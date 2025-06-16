<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    protected $table = 'journals';
    protected $fillable = [
        'current_date',
        'current_time',
        'action',
        'status',
        'request_data',
        'message',
    ];
}
