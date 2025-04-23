<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Core\Abstracts\AbstractOperations;
use App\Http\Controllers\Controller;
use App\Http\Resources\Account\AccountResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LogoutAccountController extends AbstractOperations
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            Auth::logout();
            $request->session()->regenerate();

            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes(['status' => 'Log out success']),
                    ...self::metaData($request, $request->all())
                ],
                status: Response::HTTP_OK
            );
        } else {
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes(['status' => 'User not logged in']),
                    ...self::metaData($request, $request->all())
                ],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getEntityClass(): string
    {
        return User::class;
    }

    public function getResourceClass(): string
    {
        return AccountResource::class;
    }
}
