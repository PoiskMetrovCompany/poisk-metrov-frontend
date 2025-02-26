<div class="search-grid container-mobile-small">
    @include('buttons.bordered', [
        'buttonId' => 'show-filters-menu-mobile',
        'buttonIcon' => 'filters-button',
        'buttonText' => 'Фильтры',
    ])
    @include('buttons.bordered', [
        'buttonId' => 'show-best-offers-on-map-mobile',
        'buttonIcon' => 'paper-map',
        'buttonText' => 'На карте',
    ])
</div>
