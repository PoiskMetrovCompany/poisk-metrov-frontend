<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class ListUserController extends Controller
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @OA\Schema(
     *       schema="User/List",
     *       @OA\Property(
     *           property="status",
     *           type="string"
     *       ),
     *   	@OA\Property(
     *         property="error",
     *         type="string"
     *       )
     *  ),
     *
     * @OA\Get(
         * tags={"User"},
         * path="/api/v1/users/list",
         * summary="получение списка пользователей (клиенты)",
         * description="Возвращение JSON объекта",
         * @OA\Response(
             * response=200,
             * description="УСПЕХ!",
        * ),
         * @OA\Response(
             * response=404,
             * description="Resource not found"
         * )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $users = $this->userRepository->list([]);
        return new JsonResponse(
            data: UserResource::collection($users),
            status: Response::HTTP_OK
        );
    }
}
