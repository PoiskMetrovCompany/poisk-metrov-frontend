<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $table = 'buildings';

    protected $fillable = [
        'key',
        'complex_key',
        'building_materials',
        'building_state',
        'building_phase',
        'building_section',
        'floors_total',
        'latitude',
        'longitude',
        'ready_quarter',
        'built_year',
    ];
}


