@include('catalogue.dropdown', [
    'id' => 'filter-toilet',
    'title' => 'Санузел',
    'options' => $searchData->dropdownData->bathroom_unit,
    'optionsTemplate' => 'dropdown.options.catalogue',
])
