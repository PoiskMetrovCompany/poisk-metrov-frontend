<?php

namespace App\Http\Controllers\Api\V1\Favorite;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\LikeSwitchRequest;
use App\Http\Resources\Favorite\FavoriteResource;
use App\Models\UserFavoriteBuilding;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class SwitchLikeController extends AbstractOperations
{
    /**
     * @param FavoritesServiceInterface $favoritesService
     */
    public function __construct(
        protected FavoritesServiceInterface $favoritesService,
    ) {
    }

    /**
     * @OA\Post(
     *       tags={"Favorite"},
     *       path="/api/v1/favorites/switch-like",
     *       summary="Добавить в избранное",
     *       description="Возвращение JSON объекта",
     *       security={{"bearerAuth":{}}},
     *       @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="user_key", type="string", example=""),
     *              @OA\Property(property="code", type="string", example=""),
     *              @OA\Property(property="type", type="string", example="..."),
     *              @OA\Property(property="action", type="string", example="..."),
     *          )
     *        ),
     *       @OA\Response(response=200, description="УСПЕХ!"),
     *       @OA\Response(
     *           response=404,
     *           description="Resource not found"
     *       )
     *  )
     *
     * @param LikeSwitchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LikeSwitchRequest $request): JsonResponse
    {

        $type = $request->validated('type');
        $code = $request->validated('code');
        $action = $request->validated('action');
        $user_key = $request->validated('user_key');

        try {
            $this->favoritesService->switchLike($type, $code, $action, $user_key);
            
            return response()->json([
                'success' => true,
                'message' => 'Операция выполнена успешно',
                'data' => $this->favoritesService->countFavoritesDetailed($user_key)->getData(true)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Operation failed',
                'message' => 'Ошибка при выполнении операции: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getEntityClass(): string
    {
        return UserFavoriteBuilding::class;
    }

    public function getResourceClass(): string
    {
        return FavoriteResource::class;
    }
}
