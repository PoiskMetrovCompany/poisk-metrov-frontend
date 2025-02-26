<?php

namespace App\Models\Avito;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AvitoBuilding extends Model
{
    use HasFactory;

    protected $fillable = [
        'complex_id',
        'floors_total',
        'building_materials',
        'building_section',
        'latitude',
        'longitude',
    ];

    public function residentialComplex(): BelongsTo
    {
        return $this->belongsTo(AvitoResidentialComplex::class, 'complex_id');
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(AvitoApartment::class, 'building_id');
    }
}
