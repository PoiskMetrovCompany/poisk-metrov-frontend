<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAdsAgreement extends Model 
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'name',
        'agreement'
    ];
}