<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApartmentHistory extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'Apartment' => ['main_table_value' => 'apartment_id', 'linked_table_value' => 'id'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'apartment_id',
        'price',
        'created_at'
    ];

    public function apartment() : BelongsTo
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }
}
