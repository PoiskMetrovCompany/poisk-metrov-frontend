<?php

namespace App\Models\Version2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version2Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'complex_id',
        'url'
    ];
}
