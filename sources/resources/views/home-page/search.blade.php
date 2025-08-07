@php
    $city = $cityService->where[$cityService->getUserCity()];

    if (!isset($searchData)) {
        $searchData = $searchService->getSearchData();
    }

    if (!isset($searchSubclass)) {
        $searchSubclass = '';
    }
@endphp

<div class="search-section base-container{{ $searchSubclass }}">
    @vite('resources/js/homePage/homePageFilters.js')
    <div class="search-section header">
        <h2 class="title">
            @if ($selectedCity != 'far-east')
                Недвижимость в {{ $city }}
            @else
                Недвижимость на Дальнем Востоке
            @endif
        </h2>
        @include('buttons.bordered', [
            'buttonId' => 'show-best-offers-on-map',
            'buttonIcon' => 'paper-map',
            'buttonText' => 'На карте',
        ])
    </div>
    <div class="search-section searchbar-with-button">
        @include('home-page.search-filter-menu', ['searchData' => json_decode($searchData)])
    </div>
    @include('home-page.primary-buttons-mobile')
</div>
