<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'BookedOrder' => ['main_table_value' => 'key', 'linked_table_value' => 'reservation_key'],
        'Interaction' => ['main_table_value' => 'key', 'linked_table_value' => 'reservation_key'],
    ];

    protected $fillable = [
        'key',
    ];
}
