<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BestOffer extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'ResidentialComplex' => ['main_table_value' => 'complex_code', 'linked_table_value' => 'code'],
    ];

    protected $fillable = ['location_code', 'complex_code'];
}
