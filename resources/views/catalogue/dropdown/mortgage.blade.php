@include('catalogue.dropdown', [
    'id' => 'filter-mortgage',
    'title' => 'Способы оплаты',
    'options' => $searchData->dropdownData->mortgages,
    'optionsTemplate' => 'dropdown.options.catalogue',
])
