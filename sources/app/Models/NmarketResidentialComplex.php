<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NmarketResidentialComplex extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'builder',
        'feed_gallery',
        'description',
        'latitude',
        'longitude',
        'location_id',
        'address',
        'metro_station',
        'metro_time',
        'metro_type'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(NmarketApartment::class, 'complex_code', 'code');
    }
}
