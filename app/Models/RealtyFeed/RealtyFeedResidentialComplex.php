<?php

namespace App\Models\RealtyFeed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class RealtyFeedResidentialComplex extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'description',
        'metro_station',
        'metro_time',
        'metro_type',
        'builder',
        'location_id'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(RealtyFeedLocation::class, 'location_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(RealtyFeedImage::class, 'complex_id');
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(RealtyFeedBuilding::class, 'complex_id');
    }

    public function apartments(): HasManyThrough
    {
        return $this->hasManyThrough(RealtyFeedApartment::class, RealtyFeedBuilding::class, 'complex_id', 'building_id');
    }
}
