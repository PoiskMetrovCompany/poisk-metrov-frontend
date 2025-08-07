<?php

namespace App\Models\Version2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Version2ResidentialComplex extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'description',
        'builder',
        'location_id'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Version2Location::class, 'location_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Version2Image::class, 'complex_id');
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(Version2Building::class, 'complex_id');
    }

    public function apartments(): HasManyThrough
    {
        return $this->hasManyThrough(Version2Apartment::class, Version2Building::class, 'complex_id', 'building_id');
    }
}
