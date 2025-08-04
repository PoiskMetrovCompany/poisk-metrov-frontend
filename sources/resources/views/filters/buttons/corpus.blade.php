@include('filters.buttons-grid', [
    'id' => 'corpus-buttons',
    'buttonsTitle' => 'Корпус',
    'elements' => $searchData->dropdownData->corpus,
])
