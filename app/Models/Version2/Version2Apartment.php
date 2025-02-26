<?php

namespace App\Models\Version2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Version2Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'building_id',
        'apartment_type',
        'renovation',
        'floor',
        'apartment_number',
        'plan_url',
        'room_count',
        'price',
        'area',
        'living_space',
        'kitchen_space'
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Version2Building::class, 'building_id');
    }
}
