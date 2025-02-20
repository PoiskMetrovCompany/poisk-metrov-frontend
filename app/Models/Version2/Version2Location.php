<?php

namespace App\Models\Version2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version2Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'region',
        'code',
        'capital',
        'district',
        'locality'
    ];
}
