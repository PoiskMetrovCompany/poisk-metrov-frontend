@include('custom-elements.select', [
    'id' => 'catalogue-filters-metro',
    'placeholder' => 'Расстояние до метро',
    'allData' => $searchData->dropdownData->metro,
])
