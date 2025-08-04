<div id="search-bar" class="search-bar base-container">
    <div class="filter apply">
        @include('buttons.submit', ['buttonText' => "Показать {$searchData->apartment_count} квартир"])
    </div>
    @include('search.city-text-search')
    @include('search.type-specific-searches')
    <div class="search-grid search-container">
        @include('search.dropdown.price')
        @include('search.dropdown.rooms')
        @include('filters.range.price')
        @include('search.dropdown.years')
    </div>
    @include('filters.buttons.rooms')
    @include('filters.buttons.years')
</div>
