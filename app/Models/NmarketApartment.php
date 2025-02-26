<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NmarketApartment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'offer_id',
        'complex_code',
        'apartment_type',
        'renovation',
        'balcony',
        'bathroom_unit',
        'floor',
        'floors_total',
        'apartment_number',
        'building_materials',
        'building_state',
        'building_phase',
        'building_section',
        'latitude',
        'longitude',
        'ready_quarter',
        'built_year',
        'plan_URL',
        'ceiling_height',
        'room_count',
        'price',
        'area',
        'living_space',
        'kitchen_space',
        'floor_plan_url'
    ];

    public function residentialComplex(): BelongsTo
    {
        return $this->belongsTo(NmarketResidentialComplex::class, 'complex_code');
    }
}
