<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use OpenApi\Annotations as OA;

class UpdateUserController extends AbstractOperations
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {

    }

    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/v1/users/update",
     *     summary="Обновление пользователя.",
     *     description="Возвращение JSON объекта",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для обновления пользователя",
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
     *             @OA\Property(property="key", type="string", example="e8ff11fa-822b-11f0-8411-10f60a82b815"),
     *             @OA\Property(property="name", type="string", example="Андрей"),
     *             @OA\Property(property="surname", type="string", example="Шихавцов"),
                * @OA\Property(property="patronymic", type="string", example="Александрович"),
                * @OA\Property(property="email", type="string", example="mail@mail.ru"),
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
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function __invoke(UpdateUserRequest $request): JsonResponse
    {
        // return new JsonResponse([123]);
        $user = $request->validated();
        $repository = $this->userRepository->findByKey($user['key']);

        if (empty($repository->id)) {

            return new JsonResponse(
                data: [
                    ...self::identifier(),
//                    ...self::attributes(
//                        $repository
//                    ),
                    ...self::metaData($request, $request->all()),
                ],
                status: Response::HTTP_NOT_FOUND
            );
        }

        $repository->update($user);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes(
                    $repository
                ),
                ...self::metaData($request, $request->all()),

            ],
            status: Response::HTTP_CREATED
        );
    }

    public function getEntityClass(): string
    {
        return User::class;
    }

    public function getResourceClass(): string
    {
        return UserResource::class;
    }
}
