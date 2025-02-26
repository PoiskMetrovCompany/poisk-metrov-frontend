<?php

namespace App\Models\Complex;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplexBuilding extends Model
{
    use HasFactory;

    protected $fillable = [
        'complex_id',
        //building id in xml
        'native_id',
        'address',
        'floors_total',
        'building_materials',
        'building_section',
        'building_state',
        'ready_quarter',
        'built_year',
        'latitude',
        'longitude',
    ];

    public function residentialComplex(): BelongsTo
    {
        return $this->belongsTo(ComplexResidentialComplex::class, 'complex_id');
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(ComplexApartment::class, 'building_id');
    }
}
