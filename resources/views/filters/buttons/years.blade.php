@include('filters.buttons-grid', [
    'id' => 'years-buttons',
    'buttonsTitle' => 'Срок сдачи',
    'containerClass' => 'filter-toggles buttons-container',
    'elements' => $searchData->dropdownData->years,
])
