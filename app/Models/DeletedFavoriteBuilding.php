<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedFavoriteBuilding extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'complex_code'];

    public static function createForCurrentUser(string $complexCode)
    {
        $userId = Auth::id();

        if ($userId == null) {
            return;
        }

        if (! User::where(['id' => $userId])->exists()) {
            return;
        }

        $conditions = [
            'user_id' => $userId,
            'complex_code' => $complexCode
        ];

        if (! DeletedFavoriteBuilding::where($conditions)->exists()) {
            DeletedFavoriteBuilding::create($conditions);
        }
    }
}
