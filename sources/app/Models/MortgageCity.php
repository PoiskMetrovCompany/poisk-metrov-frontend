<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MortgageCity extends Model
{
    use HasFactory;

    protected $fillable = ['city', 'mortgage_id'];

    /**
     * Получить город, к которому относится ипотечная программа.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(Cities::class, 'city', 'slug');
    }
}
