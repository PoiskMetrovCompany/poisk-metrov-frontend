<div class="filter type-specific-filters">
    @include('search.type-specific-filter', [
        'id' => 'filter-district',
        'icon' => 'search-location',
        'previewName' => 'Район',
        'searchDataPart' => $searchData->districts,
        'dropdownId' => 'filter-district-dropdown',
        'dropdownContext' => 'Район',
    ])
    @include('search.type-specific-filter', [
        'id' => 'filter-builder',
        'icon' => 'search-builder',
        'previewName' => 'Застройщик',
        'searchDataPart' => $searchData->builders,
        'dropdownId' => 'filter-builder-dropdown',
        'dropdownContext' => 'Застройщик',
    ])
    @include('search.type-specific-filter', [
        'id' => 'filter-building',
        'icon' => 'search-building',
        'previewName' => 'ЖК',
        'searchDataPart' => $searchData->names,
        'dropdownId' => 'filter-building-dropdown',
        'dropdownContext' => 'ЖК',
    ])
    @include('search.type-specific-filter', [
        'id' => 'filter-street',
        'icon' => 'search-street',
        'previewName' => 'Улица',
        'searchDataPart' => $searchData->addresses,
        'dropdownId' => 'filter-street-dropdown',
        'dropdownContext' => 'Улица',
    ])
    @include('search.type-specific-filter', [
        'id' => 'filter-metro',
        'icon' => 'search-metro',
        'previewName' => 'Метро',
        'searchDataPart' => $searchData->stations,
        'dropdownId' => 'filter-metro-dropdown',
        'dropdownContext' => 'Метро',
    ])
</div>
