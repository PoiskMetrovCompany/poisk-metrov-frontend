<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Core\Common\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\AccountSetCodeRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     tags={"Account"},
 *     path="/api/v1/account/set-code",
 *     summary="Вход для кандидата.",
 *     description="Возвращение JSON объекта",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Данные для отправки кода",
 *         @OA\JsonContent(
 *             @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="УСПЕХ!",
 *         @OA\JsonContent(
 *             @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
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
 * @param AccountSetCodeRequest $request
 * @return JsonResponse
 */
class AccountSetCodeController extends Controller
{
    public function __construct(
        protected Account $account,
    )
    {

    }

    /**
     * @param AccountSetCodeRequest $request
     * @return JsonResponse
     */
    public function __invoke(AccountSetCodeRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        if (empty($this->account::where(['phone' => $attributes['phone']])->first())) {
            $attributes['key'] = Str::uuid()->toString();
            $attributes['role'] = RoleEnum::Candidate;
            $this->account::create($attributes);
            return $this->setCode($request);
        } else {
            // TODO: генерация и отправка кода
            $code = '000000';
            $model = $this->account::where(['phone' => $attributes['phone']])->first();
            $model->update(['secret' => Hash::make($code)]);
        }
        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => AccountResource::make($model),
            ],
            status:  Response::HTTP_CREATED
        );
    }
}
