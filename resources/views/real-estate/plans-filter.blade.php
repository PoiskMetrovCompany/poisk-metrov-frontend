<div class="plans-filter base-container">
    @vite('resources/js/realEstate/filters.js')
    @vite('resources/js/realEstate/apartmentCardDropdowns.js')
    <h2 class="real-estate title">Квартиры и цены</h2>
    @include('buttons.bordered', [
        'buttonId' => 'show-filters-menu-mobile',
        'buttonIcon' => 'filters-button',
        'buttonText' => 'Фильтры',
    ])
    <div class="plans-filter filters-container">
        @include('real-estate.main-filters')
        @include('filters.buttons.years')
        @include('real-estate.secondary-filters')
    </div>
    @include('search.filter-bubbles')
    <div id="apartment-dropdowns-container" class="plans-filter apartment-dropdown base-container">
        {!! $apartmentViews !!}
    </div>
    @include('common.loader-dots', ['id' => 'apartments-loader-dots'])
    <div id="nothing-found" class="catalog nothing-found" @if ($apartmentViews == '') style="display: block" @endif>
        Квартир с такими параметрами не найдено
    </div>
</div>
