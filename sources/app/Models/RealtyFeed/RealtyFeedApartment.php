<?php

namespace App\Models\RealtyFeed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealtyFeedApartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'building_id',
        'property_type',
        'apartment_type',
        'renovation',
        'balcony',
        'bathroom_unit',
        'floor',
        'apartment_number',
        'plan_url',
        'floor_plan_url',
        'ceiling_height',
        'room_count',
        'price',
        'area',
        'living_space',
        'kitchen_space'
    ];

    protected $hidden = [
        'laravel_through_key',
        'created_at',
        'updated_at'
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(RealtyFeedBuilding::class, 'building_id');
    }
}
