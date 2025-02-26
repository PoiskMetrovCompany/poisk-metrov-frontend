<?php

namespace App\Models\Complex;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplexImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'complex_id',
        'url'
    ];
}
