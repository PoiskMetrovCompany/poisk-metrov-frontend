<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Account extends Model
{
    use HasApiTokens, HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'last_name',
        'first_name',
        'middle_name',
        'role',
        'phone',
        'email',
        'secret',
    ];

    /**
     * @var string[]
     */
    protected $dates = ['deleted_at'];

    static function createBearerToken($userAccount)
    {
        $userAccount->tokens()->delete();
        return $userAccount->createToken('user_account_token')->plainTextToken;
    }

    static function deleteBearerToken($userAccount)
    {
        return $userAccount->tokens()->delete();
    }
}
