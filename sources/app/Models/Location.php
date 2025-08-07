<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    const RELATIONSHIP = [
        'ResidentialComplex' => ['main_table_value' => 'id', 'linked_table_value' => 'location_id'],
        'NmarketResidentialComplex' => ['main_table_value' => 'id', 'linked_table_value' => 'location_id'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['country', 'region', 'code', 'capital', 'district', 'locality'];

    public static $searchableFields = ['locality', 'locality-not', 'district', 'region', 'capital'];
}
