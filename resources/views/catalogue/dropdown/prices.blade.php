@include('catalogue.dropdown', [
    'id' => 'filter-price',
    'title' => 'Цена',
    'hideCounter' => 'true',
    'options' => $searchData->dropdownData->prices,
    'optionsTemplate' => 'dropdown.options.compare',
])
