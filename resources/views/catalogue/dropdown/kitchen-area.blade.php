@include('catalogue.dropdown', [
    'id' => 'filter-kitchen-area',
    'title' => 'Площадь кухни',
    'hideCounter' => 'true',
    'options' => $searchData->dropdownData->kitchen_area,
    'optionsTemplate' => 'dropdown.options.compare',
])
