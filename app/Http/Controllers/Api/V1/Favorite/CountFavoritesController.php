<?php

namespace App\Http\Controllers\Api\V1\Favorite;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Favorite\FavoriteResource;
use App\Models\UserFavoriteBuilding;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CountFavoritesController extends AbstractOperations
{
    /**
     * @param FavoritesServiceInterface $favoritesService
     */
    public function __construct(
        protected FavoritesServiceInterface $favoritesService,
    ) {
    }

    /**
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->favoritesService->countFavorites();
        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($data),
                ...self::metaData($request, $request->all())
            ],
            status: Response::HTTP_OK
        );
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
