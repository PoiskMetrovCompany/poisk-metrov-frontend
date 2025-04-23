<?php

namespace App\Http\Controllers\Api\V1\Feeds;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\FeedServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedRequest;
use App\Http\Resources\Feeds\FeedResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteFeedController extends AbstractOperations
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
    public function __invoke(FeedRequest $request): JsonResponse
    {
        $this->feedService->deleteFeedEntry($request->validated());
        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes([]),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_NO_CONTENT
        );
    }

    public function getEntityClass(): string
    {
        return 'Feed';
    }

    public function getResourceClass(): string
    {
        return FeedResource::class;
    }
}
