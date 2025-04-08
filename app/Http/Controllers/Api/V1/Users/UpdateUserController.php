<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use OpenApi\Annotations as OA;

class UpdateUserController extends Controller
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {

    }

    /**
     * @OA\Schema(
        * schema="User/Update",
        * @OA\Property(
            * property="status",
            * type="string"
        * ),
        * @OA\Property(
            * property="error",
            * type="string"
        * )
     * ),
     *
     * @OA\Post(
         * tags={"User"},
         * path="/api/v1/users/update",
         * summary="обновление роли пользователя.",
         * description="Возвращение JSON объекта",
         * @OA\Response(
             * response=200,
             * description="УСПЕХ!",
         * ),
         * @OA\Response(
             * response=404,
             * description="Resource not found",
             *  @OA\JsonContent(
                 *   @OA\Property(property="phone", type="string", example="+7 (991) 000-00-00"),
                 *   @OA\Property(property="name", type="string", example="Андрей"),
                 *   @OA\Property(property="surname", type="string", example="Шихавцов")
             *   )
         * )
     * )
 * @param UpdateUserRequest $updateUserRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateUserRequest $updateUserRequest): JsonResponse
    {
        $user = $updateUserRequest->validated();
        $repository = $this->userRepository->findByPhone($user->phone);

        if (empty($repository->id)) {

            return new JsonResponse(
                data: [
                    'user' => new UserResource($repository),
                    'auth_id' => Auth::id()
                ],
                status: Response::HTTP_CREATED
            );
        }

        $repository->update($user);

        return new JsonResponse(
            data: [
                'user' => new UserResource($repository),
                'auth_id' => Auth::id(),
                'status' => 'User updated'
            ],
            status: Response::HTTP_CREATED
        );
    }
}
