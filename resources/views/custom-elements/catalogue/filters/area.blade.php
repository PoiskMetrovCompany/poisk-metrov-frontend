@include('custom-elements.select', [
    'id' => 'catalogue-filters-area',
    'placeholder' => 'Площадь',
    'allData' => $searchData->dropdownData->years,
])
