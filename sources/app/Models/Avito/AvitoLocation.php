<?php

namespace App\Models\Avito;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvitoLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'region',
        'code',
        'capital',
        'district',
        'locality'
    ];
}
