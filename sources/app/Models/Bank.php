<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'display_name',
        'transliterated_name',
        'original_id'
    ];

    public function mortgagePrograms(): HasMany
    {
        return $this->hasMany(Mortgage::class);
    }
}
