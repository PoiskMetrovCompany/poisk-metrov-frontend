<?php

namespace App\Models\Complex;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplexApartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'building_id',
        'price',
        'floor',
        'area',
        'apartment_number',
        'renovation',
        'balcony',
        'plan_url',
        'room_count',
        'living_space',
        'kitchen_space',
        'bathroom_unit'
    ];

    protected $hidden = [
        'laravel_through_key',
        'created_at',
        'updated_at'
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(ComplexBuilding::class, 'building_id');
    }
}
