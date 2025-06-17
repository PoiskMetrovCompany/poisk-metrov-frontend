<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'status',
        'name',
        'found_objects',
        'loaded_objects',
    ];
}
