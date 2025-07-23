<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\AccountLoginRequest;
use App\Http\Requests\Accounts\AccountSetCodeRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     tags={"Account"},
 *     path="/api/v1/account/auth",
 *     summary="Отправка кода для кандидата.",
 *     description="Возвращение JSON объекта",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Данные для входа кандидата и безопасника",
 *         @OA\JsonContent(
 *             @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
 *             @OA\Property(property="code", type="string", example="000000")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="УСПЕХ!",
 *         @OA\JsonContent(
 *             @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
 *             @OA\Property(property="code", type="string", example="000000")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Resource not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Пользователь не найден")
 *         )
 *     )
 * )
 *
 * @param AccountLoginRequest $request
 * @return JsonResponse
 */
class AccountAuthorizationController extends Controller
{
    public function __construct(
        protected Account $account,
    )
    {

    }

    public function __invoke(AccountLoginRequest $request)
    {
        $attributes = $request->validated();
        $model = $this->account::where(['phone' => $attributes['phone']])->first();

        if (
            (key_exists('phone', $attributes) && key_exists('code', $attributes) && Hash::check($attributes['code'], $model->secret)) ||
            (key_exists('email', $attributes) && key_exists('password', $attributes) && Hash::check($attributes['password'], $model->secret))
        ) {
            $account = $this->account::createBearerToken($model);
        }

        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => [
                    'user' => AccountResource::make($model),
                    'access_token' => $account,
                ],
            ],
            status: Response::HTTP_CREATED
        );
    }
}
