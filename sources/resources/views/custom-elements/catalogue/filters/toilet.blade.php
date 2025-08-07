@include('custom-elements.select', [
    'id' => 'catalogue-filters-toilet',
    'placeholder' => 'Санузел',
    'allData' => $searchData->dropdownData->bathroom_unit,
])
