@include('custom-elements.select', [
    'id' => 'catalogue-filters-rooms',
    'legend' => 'Количество комнат',
    'placeholder' => 'Кол-во комнат',
    'allData' => $searchData->dropdownData->rooms,
])
