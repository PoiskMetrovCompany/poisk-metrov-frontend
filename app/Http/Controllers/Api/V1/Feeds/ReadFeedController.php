<?php

namespace App\Http\Controllers\Api\V1\Feeds;

use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\FeedServiceInterface;
use App\FeedParsers\FeedFormat;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeedResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReadFeedController extends Controller
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeeds(Request $request): JsonResponse
    {
        return new JsonResponse(
            data: [
                'feeds' => FeedResource::collection($this->feedService->getFeeds()->sortBy('created_at', SORT_REGULAR, true)),
                'cities' => $this->cityService->possibleCityCodes,
                'feedFormats' => FeedFormat::cases()
            ],
            status: Response::HTTP_OK
        );
    }
}
