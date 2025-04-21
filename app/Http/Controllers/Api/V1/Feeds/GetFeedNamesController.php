<?php

namespace App\Http\Controllers\Api\V1\Feeds;

use App\Core\Interfaces\Services\FeedServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeedNameResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetFeedNamesController extends Controller
{
    /**
     * @param FeedServiceInterface $feedService
     */
    public function __construct(
        private FeedServiceInterface $feedService
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getFeedNames(Request $request): JsonResponse
    {
        return new JsonResponse(
            data: FeedNameResource::collection($this->feedService->getFeedNames()->sortBy('created_at', SORT_REGULAR, true)),
            status: Response::HTTP_OK
        );
    }
}
