<?php

namespace App\Models\Complex;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplexLocation extends Model
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
