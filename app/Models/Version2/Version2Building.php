<?php

namespace App\Models\Version2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Version2Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'complex_id',
        'address',
        'floors_total',
        'building_section',
        'latitude',
        'longitude',
        'built_year'
    ];

    public function residentialComplex(): BelongsTo
    {
        return $this->belongsTo(Version2ResidentialComplex::class, 'complex_id');
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(Version2Apartment::class, 'building_id');
    }
}
