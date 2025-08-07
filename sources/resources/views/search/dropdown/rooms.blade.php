@include('search.primary-dropdown', [
    'id' => 'filter-rooms-number',
    'title' => 'Кол-во комнат',
    'options' => $searchData->dropdownData->rooms,
    'optionsTemplate' => 'dropdown.options.generic',
])
