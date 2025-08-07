<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'Apartment' => ['main_table_value' => 'apartment_id', 'linked_table_value' => 'id'],
        'Manager' => ['main_table_value' => 'manager_id', 'linked_table_value' => 'id'],
        'User' => ['main_table_value' => 'user_id', 'linked_table_value' => 'id'],
        'Reservation' => ['main_table_value' => 'reservation_key', 'linked_table_value' => 'key'],
    ];

    protected $fillable = [
        'manager_id',
        'user_id',
        'apartment_id',
        'key',
        'reservation_key',
    ];
}
