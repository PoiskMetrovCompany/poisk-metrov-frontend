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
     * @param LikeSwitchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LikeSwitchRequest $request)
    {
        $user = Auth::user();

        if ($user) {
            $type = $request->validated('type');
            $code = $request->validated('code');
            $action = $request->validated('action');
            $this->favoritesService->switchLike($type, $code, $action);
        }

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($this->favoritesService->countFavoritesDetailed()),
                ...self::metaData($request, $request->all())
            ]
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
