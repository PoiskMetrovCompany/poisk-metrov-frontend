<?php

namespace App\Models\RealtyFeed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealtyFeedImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'complex_id',
        'tag',
        'url'
    ];
}
