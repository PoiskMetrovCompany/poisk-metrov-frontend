<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'buildings';

    protected $fillable = [
        'address',
        'key',
        'complex_key',
        'building_materials',
        'building_state',
        'building_section',
        'floors_total',
        'latitude',
        'longitude',
        'ready_quarter',
        'built_year',
    ];
}


