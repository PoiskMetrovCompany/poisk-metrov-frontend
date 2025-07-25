<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavoritePlan extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'Apartment' => ['main_table_value' => 'complex_code', 'linked_table_value' => 'code'],
        'User' => ['main_table_value' => 'offer_id', 'linked_table_value' => 'id'],
    ];

    protected $fillable = ['user_id', 'offer_id'];
}
