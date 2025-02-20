<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpriteImagePosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'filepath',
        'x',
        'y',
        'size_x',
        'size_y',
    ];
}
