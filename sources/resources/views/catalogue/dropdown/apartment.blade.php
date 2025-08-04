@include('catalogue.dropdown', [
    'id' => 'filter-apartments',
    'title' => 'Апартаменты',
    'options' => $searchData->dropdownData->apartments,
    'optionsTemplate' => 'dropdown.options.catalogue',
])
