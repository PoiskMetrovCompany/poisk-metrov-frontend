<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;

//use App\Http\Resources\UserResource;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class ListUserController extends AbstractOperations
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @OA\Get(
     *     tags={"User"},
     *     path="/api/v1/users/list",
     *     summary="Получение списка пользователей (клиенты)",
     *     description="Возвращение JSON объекта с возможностью включения связанных данных через параметр includes",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="includes",
     *         in="query",
     *         description="Указывает, какие связанные данные нужно включить",
     *         required=false,
     *         style="form",
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *                 enum={
     *                      "UserFavoriteBuilding",
     *                      "CRMSyncRequiredForUser",
     *                      "ResidentialComplexFeedSiteName",
     *                      "DeletedFavoriteBuilding",
     *                      "File",
     *                      "ManagerChatMessage",
     *                      "News",
     *                      "VisitedPage",
     *                      "UserFavoritePlan",
     *                      "Manager",
     *                      "Interaction"
     *                  }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="УСПЕХ!",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found"
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $users = $this->userRepository->list([]);
        $collect = UserResource::collection($users);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
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
