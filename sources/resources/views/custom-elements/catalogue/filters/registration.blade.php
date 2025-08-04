@include('custom-elements.select', [
    'id' => 'catalogue-filters-registration',
    'placeholder' => 'Прописка',
    'allData' => $searchData->dropdownData->registration,
])
