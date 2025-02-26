@include('custom-elements.select', [
    'id' => 'catalogue-filters-finishing',
    'placeholder' => 'Отделка',
    'allData' => $searchData->dropdownData->finishing,
])
