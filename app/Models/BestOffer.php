<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestOffer extends Model
{
    use HasFactory;

    protected $fillable = ['location_code', 'complex_code'];
}
