@include('custom-elements.select', [
    'id' => 'catalogue-filters-floor',
    'placeholder' => 'Этаж',
    'allData' => $searchData->dropdownData->years,
])
