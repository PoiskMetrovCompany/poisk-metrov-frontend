<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Services\CachingServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\RealEstateServiceInterface;
use App\Core\Interfaces\Services\SearchServiceInterface;
use App\Http\Requests\GetFilteredCatalogueRequest;
use App\Providers\AppServiceProvider;
use App\Repositories\ResidentialComplexRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @see AppServiceProvider::registerCachingService()
 * @see AppServiceProvider::registerRealEstateService()
 * @see AppServiceProvider::registerSearchService()
 * @see AppServiceProvider::registerCityService()
 * @see CachingServiceInterface
 * @see RealEstateServiceInterface
 * @see SearchServiceInterface
 * @see CityServiceInterface
 */
class CatalogueViewController extends Controller
{
    /**
     * @param CachingServiceInterface $cachingService
     * @param RealEstateServiceInterface $realEstateService
     * @param SearchServiceInterface $searchService
     * @param CityServiceInterface $cityService
     * @param ResidentialComplexRepository $residentialComplexRepository
     */
    public function __construct(
        protected CachingServiceInterface $cachingService,
        protected RealEstateServiceInterface $realEstateService,
        protected SearchServiceInterface $searchService,
        protected CityServiceInterface $cityService,
        protected ResidentialComplexRepository $residentialComplexRepository
    ) {
    }

    /**
     * @param GetFilteredCatalogueRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function getFilteredCatalogueWithSearchData(GetFilteredCatalogueRequest $request)
    {
        $request->cityCode = $this->cityService->getUserCity();
        $request->offset = 0;
        $request->limit = 18;
        $response = $this->getFilteredCatalogueViews($request);
        $response['filterData'] = $this->searchService->getSearchData();

        $view = view('catalogue', $response);

        try {
            return $view;
        } catch (Throwable $e) {
            Log::info('Error', [Response::view($view)]);
        }
    }

    /**
     * @param GetFilteredCatalogueRequest $request
     * @return array
     * @throws Throwable
     */
    public function getFilteredCatalogueViews(GetFilteredCatalogueRequest $request)
    {
        $request->cityCode = $this->cityService->getUserCity();
        $validated = $request->validated();
        $filteredCatalogueData = $this->realEstateService->getFilteredCatalogueData($validated, $request->cityCode);
        $filteredBuildings = $filteredCatalogueData['catalogueQueryBuilder'];
        unset($filteredCatalogueData['catalogueQueryBuilder']);
        $viewElement = 'custom-elements.building-card';

        if (isset($validated['cardelement'])) {
            $viewElement = $validated['cardelement'];
        }

        // while ($request->offset > $filteredCatalogueData['fullfilledCount'] ) {
        //     $request->offset -= $request->limit;
        // }

        $codes = $filteredBuildings
            ->offset($request->offset)
            ->limit($request->limit)
            ->get()
            ->pluck('code')
            ->toArray();
        $allCodes = $filteredCatalogueData['allCodes'];

        $cards = $this->cachingService->getCards($codes);
        $allCards = $this->cachingService->getCards($allCodes);

        $data = array_values($cards);
        $allData = array_values($allCards);
        $views = [];

        foreach ($data as $card) {
            $views[] = view($viewElement, $card)->render();
        }

        $countPages = ceil($filteredCatalogueData['fullfilledCount'] / $request->limit) + 1;
        $paginator = view('common.paginator-with-show-more', ['id' => 'catalogue-paginator', 'pageCount' => $countPages])->render();

        $filteredCatalogueData['catalogueItems'] = $views;
        $filteredCatalogueData['catalogueBuildings'] = $allData;
        $filteredCatalogueData['paginator'] = $paginator;

        return $filteredCatalogueData;
    }
}
