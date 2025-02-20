<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MortgageCity extends Model
{
    use HasFactory;

    protected $fillable = ['city', 'mortgage_id'];
}
