<?php

namespace App\Models\Complex;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ComplexResidentialComplex extends Model
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
        return $this->belongsTo(ComplexLocation::class, 'location_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ComplexImage::class, 'complex_id');
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(ComplexBuilding::class, 'complex_id');
    }

    public function apartments(): HasManyThrough
    {
        return $this->hasManyThrough(ComplexApartment::class, ComplexBuilding::class, 'complex_id', 'building_id');
    }
}
