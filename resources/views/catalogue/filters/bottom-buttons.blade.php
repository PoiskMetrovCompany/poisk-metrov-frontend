<div class="search-catalogue bottom-buttons">
    @include('search.filter-bubbles')
    @include('buttons.bordered', [
        'buttonId' => 'show-filters-menu',
        'buttonIcon' => 'filters-button',
        'buttonText' => 'Все фильтры',
    ])
    @if (!str_starts_with(Request::path(), 'agent'))
        @include('buttons.bordered', [
            'buttonId' => 'catalogue-map-button',
            'buttonIcon' => 'paper-map',
            'buttonText' => 'На карте',
        ])
    @endif
    @if ($fullfilledApartments > 0)
        @include('buttons.submit', ['buttonText' => "Показать $fullfilledApartments квартир"])
    @else
        @include('buttons.submit', ['buttonText' => 'Нет подходящих квартир'])
    @endif
</div>
