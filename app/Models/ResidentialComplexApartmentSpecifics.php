<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResidentialComplexApartmentSpecifics extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'ResidentialComplex' => ['main_table_value' => 'building_id', 'linked_table_value' => 'id'],
    ];

    protected $fillable = [
        'building_id',
        'starting_price',
        'starting_area',
        'count',
        'display_name'
    ];

    public function residentialComplex(): BelongsTo
    {
        return $this->belongsTo(ResidentialComplex::class, 'building_id');
    }
}
