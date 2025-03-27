<?php

namespace App\Http\Controllers;

use App\Core\Services\CityServiceInterface;
use App\Core\Services\FeedServiceInterface;
use App\FeedParsers\FeedFormat;
use App\Http\Requests\FeedNameRequest;
use App\Http\Requests\FeedRequest;
use App\Http\Resources\FeedNameResource;
use App\Models\ResidentialComplexFeedSiteName;
use App\Providers\AppServiceProvider;
use App\Services\CityService;
use App\Services\FeedService;
use App\Http\Resources\FeedResource;
use Illuminate\Http\Request;

/**
 * @see AppServiceProvider::registerCityService()
 * @see FeedServiceInterface
 * @see CityServiceInterface
 */
class FeedController extends Controller
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
     * @param FeedRequest $feedRequest
     * @return void
     */
    public function createFeed(FeedRequest $feedRequest)
    {
        $this->feedService->createFeedEntry($feedRequest->validated());
    }

    /**
     * @param FeedRequest $feedRequest
     * @return void
     */
    public function updateFeed(FeedRequest $feedRequest)
    {
        $this->feedService->updateFeedEntry($feedRequest->validated());
    }

    /**
     * @param FeedRequest $feedRequest
     * @return void
     */
    public function deleteFeed(FeedRequest $feedRequest)
    {
        $this->feedService->deleteFeedEntry($feedRequest->validated());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeeds(Request $request)
    {
        return response()->json([
            'feeds' => FeedResource::collection($this->feedService->getFeeds()->sortBy('created_at', SORT_REGULAR, true)),
            'cities' => $this->cityService->possibleCityCodes,
            'feedFormats' => FeedFormat::cases()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeedNames(Request $request)
    {
        return response()->json(FeedNameResource::collection($this->feedService->getFeedNames()->sortBy('created_at', SORT_REGULAR, true)));
    }

    /**
     * @param FeedNameRequest $request
     * @return void
     */
    public function updateFeedName(FeedNameRequest $request)
    {
        $this->feedService->updateFeedName($request->validated());
    }
}
