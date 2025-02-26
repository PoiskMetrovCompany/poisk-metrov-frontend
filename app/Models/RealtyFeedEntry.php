<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealtyFeedEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'format',
        'city',
        'fallback_residential_complex_name',
        'default_builder'
    ];
}
