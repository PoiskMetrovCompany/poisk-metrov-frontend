@include('catalogue.dropdown', [
    'id' => 'filter-floors',
    'title' => 'Этаж',
    'hideCounter' => 'true',
    'options' => $searchData->dropdownData->floors,
    'optionsTemplate' => 'dropdown.options.compare',
])
