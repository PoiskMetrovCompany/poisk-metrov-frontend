<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApartmentResource;
use App\Repositories\ResidentialComplexRepository;
use App\Services\ApartmentService;
use App\Services\CachingService;
use App\Services\CityService;
use App\Services\NewsService;
use App\Services\SearchService;

class HomePageController extends Controller
{
    public function __construct(
        protected SearchService $searchService,
        protected CachingService $cachingService,
        protected CityService $cityService,
        protected ApartmentService $apartmentService,
        protected NewsService $newsService,
        protected ResidentialComplexRepository $residentialComplexRepository
    ) {
    }

    public function getHomePage()
    {
        $bestOfferData = $this->residentialComplexRepository->getBestOffers();
        $catalogueData = $this->residentialComplexRepository->getCatalogueForCity($this->cityService->getUserCity());
        $bestOfferCodes = $bestOfferData->pluck('code')->toArray();
        $catalogueCodes = $catalogueData->pluck('code')->toArray();
        $bestOfferCards = $this->cachingService->getCards($bestOfferCodes);
        $catalogueCards = $this->cachingService->getCards($catalogueCodes);
        $recommendations = ApartmentResource::collection($this->apartmentService->getApartmentRecommendations());
        $recommendations = $recommendations->toArray(request());
        $news = $this->newsService->getNewsForSite();

        return view('index', [
            'catalogueItems' => $bestOfferCards,
            'allItemsInCity' => array_values($catalogueCards),
            'searchData' => $this->searchService->getSearchData(),
            'recommendations' => $recommendations,
            'news' => $news
        ]);
    }
}
