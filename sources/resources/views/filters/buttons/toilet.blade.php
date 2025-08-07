@include('filters.buttons-grid', [
    'id' => 'toilet-buttons',
    'buttonsTitle' => 'Санузел',
    'elements' => $searchData->dropdownData->bathroom_unit,
])
