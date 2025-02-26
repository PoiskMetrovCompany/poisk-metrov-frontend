<catalogue-filters>
    @php
        $searchData = json_decode($searchService->getSearchData());
    @endphp
    <div type="filter-container">
        @include('custom-elements.search-bar', ['placeholder' => 'Район, метро, улица, застройщик, ЖК'])
        @include('custom-elements.catalogue.filters.rooms')
        @include('custom-elements.catalogue.filters.price')
    </div>
    <div type="additional-filters" showall="1">
        @include('custom-elements.catalogue.filters.date')
        @include('custom-elements.catalogue.filters.floor')
        @include('custom-elements.catalogue.filters.area')
        @include('custom-elements.catalogue.filters.kitchen')
        @include('custom-elements.catalogue.filters.metro')
        @include('custom-elements.catalogue.filters.finishing')
        @include('custom-elements.catalogue.filters.toilet')
        @include('custom-elements.catalogue.filters.mortgage')
        @include('custom-elements.catalogue.filters.registration')
        @include('custom-elements.catalogue.filters.apartments')
    </div>
    <div type="bottom-buttons">
        @include('search.filter-bubbles')
        @include('buttons.bordered', [
            'buttonId' => 'show-filters-menu',
            'buttonIcon' => 'filters-button',
            'buttonText' => 'Все фильтры',
        ])
        @php
            $fullfilledApartments = 10000;
        @endphp
        @if ($fullfilledApartments > 0)
            @include('buttons.submit', ['buttonText' => "Показать $fullfilledApartments квартир"])
        @else
            @include('buttons.submit', ['buttonText' => 'Нет подходящих квартир'])
        @endif
    </div>
</catalogue-filters>
