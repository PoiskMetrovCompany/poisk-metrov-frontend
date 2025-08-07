<form class="search-grid base-container">
    @include('search.mobile-header', [
        'backButtonId' => 'close-filter-menu-button',
        'resetButtonId' => 'reset-filters-button',
    ])
    @include('search.filter-bubbles')
    @include('search.search-bar', ['searchData' => $searchData])
    @include('buttons.submit', [
        'buttonText' => "Показать {$searchData->apartment_count} квартир",
    ])
</form>
