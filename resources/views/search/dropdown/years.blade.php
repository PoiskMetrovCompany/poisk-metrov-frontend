@include('search.primary-dropdown', [
    'id' => 'filter-completion-date',
    'title' => 'Срок сдачи',
    'options' => $searchData->dropdownData->years,
    'optionsTemplate' => 'dropdown.options.generic',
])
