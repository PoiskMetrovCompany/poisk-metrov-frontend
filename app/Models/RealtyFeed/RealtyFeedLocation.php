<?php

namespace App\Models\RealtyFeed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealtyFeedLocation extends Model
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
