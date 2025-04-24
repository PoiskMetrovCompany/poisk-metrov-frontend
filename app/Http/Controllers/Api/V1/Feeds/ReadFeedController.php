<?php

namespace App\Http\Controllers\Api\V1\Feeds;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\FeedServiceInterface;
use App\FeedParsers\FeedFormat;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeedResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class ReadFeedController extends AbstractOperations
{
    /**
     * @param FeedServiceInterface $feedService
     * @param CityServiceInterface $cityService
     */
    public function __construct(
        private FeedServiceInterface $feedService,
        private CityServiceInterface $cityService
    ) {
    }

    /**
     * @OA\Get(
     *        tags={"Feed"},
     *        path="/api/v1/feeds/read",
     *        summary="получение конкретного фида",
     *        description="Возвращение JSON объекта",
     *        security={{"bearerAuth":{}}},
     *        @OA\Response(response=200, description="УСПЕХ!"),
     *        @OA\Response(
     *            response=404,
     *            description="Resource not found"
     *        )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes([
                    'feeds' => FeedResource::collection($this->feedService->getFeeds()->sortBy('created_at', SORT_REGULAR, true)),
                    'cities' => $this->cityService->possibleCityCodes,
                    'feedFormats' => FeedFormat::cases()
                ]),
                ...self::metaData($request, $request->all())
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
        return \App\Http\Resources\Feeds\FeedResource::class;
    }
}
