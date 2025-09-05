<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class UserFilter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'user_key',
        'type',
        'rooms',
        'price',
        'floors',
        'area_full',
        'area_living',
        'area_plot',
        'ceiling_height',
        'house_type',
        'finishing',
        'bathroom',
        'features',
        'security',
        'water_supply',
        'electricity',
        'sewerage',
        'heating',
        'gasification',
        'to_metro',
        'to_center',
        'to_busstop',
        'to_train',
        'near',
        'garden_community',
        'in_city',
        'payment_method',
        'mortgage',
        'installment_plan',
        'down_payment',
        'mortgage_programs'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'garden_community' => 'boolean',
        'in_city' => 'boolean',
        'rooms' => 'integer'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->key)) {
                $model->key = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the user that owns the filter.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_key', 'key');
    }
}
