<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpriteImagePosition extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'ResidentialComplex' => ['main_table_value' => 'id', 'linked_table_value' => 'building_id'],
    ];

    protected $fillable = [
        'building_id',
        'filepath',
        'x',
        'y',
        'size_x',
        'size_y',
    ];
}
