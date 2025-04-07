<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Services\ApartmentServiceInterface;
use App\Core\Interfaces\Services\CachingServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\NewsServiceInterface;
use App\Core\Interfaces\Services\SearchServiceInterface;
use App\Http\Resources\ApartmentResource;
use App\Providers\AppServiceProvider;
use App\Repositories\ResidentialComplexRepository;

/**
 * @see AppServiceProvider::registerSearchService()
 * @see AppServiceProvider::registerCachingService()
 * @see AppServiceProvider::registerCityService()
 * @see AppServiceProvider::registerApartmentService()
 * @see AppServiceProvider::registerNewsService()
 * @see SearchServiceInterface
 * @see CachingServiceInterface
 * @see CityServiceInterface
 * @see ApartmentServiceInterface
 * @see NewsServiceInterface
 */
class HomePageController extends Controller
{
    /**
     * @param SearchServiceInterface $searchService
     * @param CachingServiceInterface $cachingService
     * @param CityServiceInterface $cityService
     * @param ApartmentServiceInterface $apartmentService
     * @param NewsServiceInterface $newsService
     * @param ResidentialComplexRepositoryInterface $residentialComplexRepository
     */
    public function __construct(
        protected SearchServiceInterface $searchService,
        protected CachingServiceInterface $cachingService,
        protected CityServiceInterface $cityService,
        protected ApartmentServiceInterface $apartmentService,
        protected NewsServiceInterface $newsService,
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository
    ) {
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
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
