<?php

namespace App\Http\Controllers\Api\V1\Feeds;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\FeedServiceInterface;
use App\Http\Requests\FeedRequest;
use App\Http\Resources\Feeds\FeedResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UpdateFeedController extends AbstractOperations
{
    /**
     * @param FeedServiceInterface $feedService
     */
    public function __construct(
        private FeedServiceInterface $feedService
    ) {
    }

    /**
     * @param FeedRequest $request
     * @return JsonResponse
     */
    public function __invoke(FeedRequest $request): JsonResponse
    {
        $this->feedService->updateFeedEntry($request->validated());
        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes([]),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
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
