<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Builder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'construction',
        'builder',
        'city'
    ];

    /**
     * Получить город, к которому относится застройщик.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(Cities::class, 'city', 'slug');
    }
}
