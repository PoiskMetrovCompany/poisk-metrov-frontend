<?php

namespace App\Models\RealtyFeed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RealtyFeedBuilding extends Model
{
    use HasFactory;

    protected $fillable = [
        'complex_id',
        'floors_total',
        'building_materials',
        'building_state',
        'building_phase',
        'building_section',
        'latitude',
        'longitude',
        'ready_quarter',
        'built_year'
    ];

    public function residentialComplex(): BelongsTo
    {
        return $this->belongsTo(RealtyFeedResidentialComplex::class, 'complex_id');
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(RealtyFeedApartment::class, 'building_id');
    }
}
