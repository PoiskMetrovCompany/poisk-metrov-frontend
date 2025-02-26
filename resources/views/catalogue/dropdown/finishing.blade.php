@include('catalogue.dropdown', [
    'id' => 'filter-finishing',
    'title' => 'Отделка',
    'options' => $searchData->dropdownData->finishing,
    'optionsTemplate' => 'dropdown.options.catalogue',
])
