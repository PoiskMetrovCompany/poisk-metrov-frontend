<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFavoriteBuilding extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'ResidentialComplex' => ['main_table_value' => 'complex_code', 'linked_table_value' => 'code'],
        'User' => ['main_table_value' => 'user_id', 'linked_table_value' => 'id'],
    ];

    protected $fillable = ['user_id', 'complex_code'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
