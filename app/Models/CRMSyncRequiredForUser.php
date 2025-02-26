<?php

namespace App\Models;

use App\Http\Controllers\UserController;
use App\Services\CityService;
use App\Services\FavoritesService;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMSyncRequiredForUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public static function createForCurrentUser()
    {
        $userId = Auth::id();

        if ($userId == null) {
            return;
        }

        $user = User::where(['id' => $userId])->first();

        if ($user == null) {
            return;
        }

        if ($user->crm_id == null) {
            $userController = new UserController(app()->get(FavoritesService::class), app()->get(CityService::class));
            $userController->createLeadForUser($user);

            if ($user->crm_id == null) {
                return;
            }
        }

        if (! CRMSyncRequiredForUser::where('user_id', $userId)->exists()) {
            CRMSyncRequiredForUser::create(['user_id' => $userId]);
        }
    }
}
