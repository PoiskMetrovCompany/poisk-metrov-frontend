@include('catalogue.dropdown', [
    'id' => 'filter-metro-distance',
    'title' => 'Расстояние до метро',
    'options' => $searchData->dropdownData->metro,
    'optionsTemplate' => 'dropdown.options.catalogue',
])
