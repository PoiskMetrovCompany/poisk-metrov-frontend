@include('custom-elements.select', [
    'id' => 'catalogue-filters-apartments',
    'placeholder' => 'Апартаменты',
    'allData' => $searchData->dropdownData->apartments,
])
