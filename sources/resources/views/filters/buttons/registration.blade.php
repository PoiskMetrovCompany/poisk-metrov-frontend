@include('filters.buttons-grid', [
    'id' => 'registration-buttons',
    'buttonsTitle' => 'Прописка',
    'elements' => $searchData->dropdownData->registration,
])
