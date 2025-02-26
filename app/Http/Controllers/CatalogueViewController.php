<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetFilteredCatalogueRequest;
use App\Repositories\ResidentialComplexRepository;
use App\Services\CachingService;
use App\Services\CityService;
use App\Services\RealEstateService;
use App\Services\SearchService;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class CatalogueViewController extends Controller
{
    public function __construct(
        protected CachingService $cachingService,
        protected RealEstateService $realEstateService,
        protected SearchService $searchService,
        protected CityService $cityService,
        protected ResidentialComplexRepository $residentialComplexRepository
    ) {
    }

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
