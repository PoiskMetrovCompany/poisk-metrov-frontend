<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealtyFeedEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'format',
        'city',
        'fallback_residential_complex_name',
        'default_builder'
    ];

    /**
     * Получить город, к которому относится фид недвижимости.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(Cities::class, 'city', 'slug');
    }
}
