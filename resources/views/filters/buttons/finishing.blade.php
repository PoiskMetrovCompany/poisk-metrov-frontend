@include('filters.buttons-grid', [
    'id' => 'finishing-buttons',
    'buttonsTitle' => 'Отделка',
    'elements' => $searchData->dropdownData->finishing,
])
