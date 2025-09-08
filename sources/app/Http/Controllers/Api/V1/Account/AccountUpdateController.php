<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\AccountUpdateRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountUpdateController extends Controller
{
    public function __construct(
        protected Account $account,
    )
    {

    }

    /**
     * @OA\Post(
     *     tags={"Account"},
     *     path="/api/v1/accounts/update",
     *     summary="Обновление аккаунта",
     *     description="Возвращение JSON объекта",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для обновления аккаунта",
     *         @OA\JsonContent(
     *             @OA\Property(property="key", type="string", example="e8ff11fa-822b-11f0-8411-10f60a82b815"),
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
     * 
     * @param AccountUpdateRequest $request
     * @return JsonResponse
     */
    public function __invoke(AccountUpdateRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $account = $this->account::update($attributes);

        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => AccountResource::make($account),
            ],
            status: Response::HTTP_CREATED
        );
    }
}
