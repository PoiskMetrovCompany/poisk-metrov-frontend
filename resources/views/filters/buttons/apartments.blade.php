@include('filters.buttons-grid', [
    'id' => 'apartments-buttons',
    'buttonsTitle' => 'Апартаменты',
    'elements' => $searchData->dropdownData->apartments,
])
