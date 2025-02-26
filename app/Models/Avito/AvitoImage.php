<?php

namespace App\Models\Avito;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvitoImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'complex_id',
        'url'
    ];
}
