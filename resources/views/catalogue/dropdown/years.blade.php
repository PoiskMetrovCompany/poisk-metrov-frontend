@include('catalogue.dropdown', [
    'id' => 'filter-years-number',
    'title' => 'Срок сдачи',
    'preview' => 'Срок сдачи',
    'options' => $searchData->dropdownData->years,
    'optionsTemplate' => 'dropdown.options.catalogue',
])
