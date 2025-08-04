<div class="base-container" id="catalogue-container">
    @php
        $whatCity = $cityService->what[$selectedCity];
        $residentialComplexes = $residentialComplexRepository->getCatalogueForCity($selectedCity)->sortBy(
            function ($complex) {
                return $complex->apartments()->count();
            },
            SORT_REGULAR,
            true,
        );
        $fullfilledApartments = $residentialComplexes->count();
        $fullfilledCount = $residentialComplexes->count();
        $buildingCount = $residentialComplexes->count();
        $codes = $residentialComplexes->pluck('code')->toArray();
        $catalogueBuildings = array_values($cachingService->getCards($codes));
        $catalogueItems = [];
        $cardElement = 'custom-elements.wide-building-card';
        $limit = 24;
        $i = 0;

        foreach ($catalogueBuildings as $buildingData) {
            if ($i >= $limit) {
                break;
            }

            $catalogueItems[] = view($cardElement, $buildingData)->render();
            $i++;
        }

        $encodedSearchData = $searchService->getSearchData();
        $searchData = json_decode($encodedSearchData);
        $excludeHeader = true;
        $countPages = ceil($fullfilledCount / $limit) + 1;
        $paginator = view('common.paginator-with-show-more', [
            'id' => 'catalogue-paginator',
            'pageCount' => $countPages,
        ])->render();
    @endphp
    <script>
        let filterData = {!! $encodedSearchData !!};
    </script>
    @vite('resources/js/catalogueFilters/filters.js')
    @include('catalogue.catalogue-filters')
    @include('catalogue.building-card-gallery', ['subtype' => 'wide'])
</div>

{{-- @include('custom-elements.catalogue-filters') --}}
