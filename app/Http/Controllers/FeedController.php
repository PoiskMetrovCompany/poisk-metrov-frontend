<?php

namespace App\Http\Controllers;

use App\FeedParsers\FeedFormat;
use App\Http\Requests\FeedNameRequest;
use App\Http\Requests\FeedRequest;
use App\Http\Resources\FeedNameResource;
use App\Models\ResidentialComplexFeedSiteName;
use App\Services\CityService;
use App\Services\FeedService;
use App\Http\Resources\FeedResource;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function __construct(
        private FeedService $feedService,
        private CityService $cityService
    ) {
    }

    public function createFeed(FeedRequest $feedRequest)
    {
        $this->feedService->createFeedEntry($feedRequest->validated());
    }

    public function updateFeed(FeedRequest $feedRequest)
    {
        $this->feedService->updateFeedEntry($feedRequest->validated());
    }

    public function deleteFeed(FeedRequest $feedRequest)
    {
        $this->feedService->deleteFeedEntry($feedRequest->validated());
    }

    public function getFeeds(Request $request)
    {
        return response()->json([
            'feeds' => FeedResource::collection($this->feedService->getFeeds()->sortBy('created_at', SORT_REGULAR, true)),
            'cities' => $this->cityService->possibleCityCodes,
            'feedFormats' => FeedFormat::cases()
        ]);
    }

    public function getFeedNames(Request $request)
    {
        return response()->json(FeedNameResource::collection($this->feedService->getFeedNames()->sortBy('created_at', SORT_REGULAR, true)));
    }

    public function updateFeedName(FeedNameRequest $request)
    {
        $this->feedService->updateFeedName($request->validated());
    }
}
