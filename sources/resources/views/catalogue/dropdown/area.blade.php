@include('catalogue.dropdown', [
    'id' => 'filter-area',
    'title' => 'Площадь',
    'hideCounter' => 'true',
    'options' => $searchData->dropdownData->area,
    'optionsTemplate' => 'dropdown.options.compare',
])
