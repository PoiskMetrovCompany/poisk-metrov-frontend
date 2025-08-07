<form class="search-catalogue base-container" autocomplete="off">
    <script>
        let catalogueBuildings = {!! json_encode($catalogueBuildings) !!}
    </script>
    @include('search.mobile-header', [
        'backButtonId' => 'close-filter-menu-button',
        'resetButtonId' => 'reset-filters-button',
    ])
    @if (!isset($excludeHeader) || $excludeHeader == false)
        @include('catalogue.header')
    @endif
    <div id="search-bar" class="search-catalogue container"
        @isset($cardElement) cardelement="{{ $cardElement }}" @endisset
        @isset($limit) limit="{{ $limit }}" @else limit="18" @endisset>
        @include('search.city-text-search')
        @include('search.type-specific-searches')
        @include('catalogue.dropdown.rooms')
        @include('filters.range.price')
        @include('filters.range.floor')
        @include('filters.range.area')
        @include('filters.range.kitchen-area')
        @include('filters.buttons.rooms')
        @include('filters.buttons.years')
        @include('filters.buttons.finishing')
        @include('filters.buttons.toilet')
        @include('filters.buttons.payment')
        @include('filters.buttons.metro')
        @include('filters.buttons.registration')
        @include('filters.buttons.apartments')
    </div>
    @include('catalogue.filters.secondary')
    @include('catalogue.filters.bottom-buttons')
</form>
