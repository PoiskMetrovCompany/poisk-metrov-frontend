@include('catalogue.dropdown', [
    'id' => 'filter-corpus',
    'title' => 'Корпус',
    'options' => $searchData->dropdownData->corpus,
    'optionsTemplate' => 'dropdown.options.catalogue',
])
