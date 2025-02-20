@include('custom-elements.select', [
    'id' => 'catalogue-filters-kitchen',
    'placeholder' => 'Площадь кухни',
    'allData' => $searchData->dropdownData->years,
])
