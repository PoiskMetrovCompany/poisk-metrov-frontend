<?php

namespace App\Http\Controllers\Api\V1\Feeds;

use App\Core\Interfaces\Services\FeedServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedNameRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateFeedNamesController extends Controller
{
    /**
     * @param FeedServiceInterface $feedService
     */
    public function __construct(
        private FeedServiceInterface $feedService
    ) {
    }

    /**
     * @param FeedNameRequest $request
     * @return JsonResponse
     */
    public function updateFeedName(FeedNameRequest $request): JsonResponse
    {
        $this->feedService->updateFeedName($request->validated());
        return new JsonResponse(
            data: [],
            status: Response::HTTP_OK
        );
    }
}
