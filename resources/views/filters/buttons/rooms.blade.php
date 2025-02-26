@include('filters.buttons-grid', [
    'id' => 'rooms-buttons',
    'buttonsTitle' => 'Количество комнат',
    'containerClass' => 'filter-toggles buttons-container rooms',
    'elements' => $searchData->dropdownData->rooms,
])
