<?php

namespace App\Http\Controllers\Api\V1\Feeds;

use App\Core\Interfaces\Services\FeedServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteFeedController extends Controller
{
    /**
     * @param FeedServiceInterface $feedService
     */
    public function __construct(
        private FeedServiceInterface $feedService
    ) {
    }

    /**
     * @param FeedRequest $feedRequest
     * @return JsonResponse
     */
    public function __invoke(FeedRequest $feedRequest): JsonResponse
    {
        $this->feedService->deleteFeedEntry($feedRequest->validated());
        return new JsonResponse(
            data: [],
            status: Response::HTTP_NO_CONTENT
        );
    }
}
