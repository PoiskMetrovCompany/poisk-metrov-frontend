<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\AccountStoreRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountStoreController extends Controller
{
    public function __construct(
        protected Account $account,
    )
    {

    }

    /**
     * @OA\Post(
     *     tags={"Account"},
     *     path="/api/v1/account/store",
     *     summary="Создание аккаунта",
     *     description="Возвращение JSON объекта",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для создания аккаунта",
     *         @OA\JsonContent(
     *             @OA\Property(property="last_name", type="string", example="Шихавцов"),
     *             @OA\Property(property="first_name", type="string", example="Андрей"),
     *             @OA\Property(property="middle_name", type="string", example="Александрович"),
     *             @OA\Property(property="email", type="string", example="mail@mail.ru"),
     *             @OA\Property(property="role", type="string", example="admin"),
     *             @OA\Property(property="password", type="string", example="password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="УСПЕХ!",
     *     )
     * )
     */
    public function __invoke(AccountStoreRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        // Преобразуем password в secret и хэшируем
        if (isset($attributes['password'])) {
            $attributes['secret'] = Hash::make($attributes['password']);
            unset($attributes['password']);
        }

        $attributes['key'] = Str::uuid()->toString();

        $account = $this->account::create($attributes);

        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => AccountResource::make($account),
            ],
            status: 201
        );
    }
}
