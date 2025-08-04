@include('custom-elements.select', [
    'id' => 'catalogue-filters-mortgages',
    'placeholder' => 'Способы оплаты',
    'allData' => $searchData->dropdownData->mortgages,
])
