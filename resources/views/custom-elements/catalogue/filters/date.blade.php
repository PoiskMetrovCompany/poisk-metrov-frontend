@include('custom-elements.select', [
    'id' => 'catalogue-filters-years',
    'placeholder' => 'Срок сдачи',
    'allData' => $searchData->dropdownData->years,
])
