@include('filters.buttons-grid', [
    'id' => 'metro-buttons',
    'buttonsTitle' => 'Расстояние до метро',
    'elements' => $searchData->dropdownData->metro,
])
