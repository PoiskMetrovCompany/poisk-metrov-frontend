<?php

namespace App\Models\Avito;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvitoApartment extends Model
{
    use HasFactory;
    protected $fillable = [
        'offer_id',
        'building_id',
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

    public function building(): BelongsTo
    {
        return $this->belongsTo(AvitoBuilding::class, 'building_id');
    }
}
