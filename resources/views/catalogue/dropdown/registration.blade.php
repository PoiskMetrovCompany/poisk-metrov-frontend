@include('catalogue.dropdown', [
    'id' => 'filter-registration',
    'title' => 'Прописка',
    'options' => $searchData->dropdownData->registration,
    'optionsTemplate' => 'dropdown.options.catalogue',
])
