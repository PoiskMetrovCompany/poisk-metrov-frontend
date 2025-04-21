<?php

namespace App\Http\Controllers\Api\V1\Favorite;

use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\LikeSwitchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwitchLikeController extends Controller
{
    /**
     * @param FavoritesServiceInterface $favoritesService
     */
    public function __construct(
        protected FavoritesServiceInterface $favoritesService,
    ) {
    }

    /**
     * @param LikeSwitchRequest $likeSwitchRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LikeSwitchRequest $likeSwitchRequest)
    {
        $user = Auth::user();

        if ($user) {
            $type = $likeSwitchRequest->validated('type');
            $code = $likeSwitchRequest->validated('code');
            $action = $likeSwitchRequest->validated('action');
            $this->favoritesService->switchLike($type, $code, $action);
        }

        return $this->favoritesService->countFavoritesDetailed();
    }
}
