@include('catalogue.dropdown', [
    'id' => 'filter-rooms-number',
    'title' => 'Количество комнат',
    'preview' => 'Кол-во комнат',
    'options' => $searchData->dropdownData->rooms,
    'optionsTemplate' => 'dropdown.options.catalogue',
])
