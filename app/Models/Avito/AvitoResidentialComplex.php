<?php

namespace App\Models\Avito;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class AvitoResidentialComplex extends Model
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
        return $this->belongsTo(AvitoLocation::class, 'location_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(AvitoImage::class, 'complex_id');
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(AvitoBuilding::class, 'complex_id');
    }

    public function apartments(): HasManyThrough
    {
        return $this->hasManyThrough(AvitoApartment::class, AvitoBuilding::class, 'complex_id', 'building_id');
    }
}
