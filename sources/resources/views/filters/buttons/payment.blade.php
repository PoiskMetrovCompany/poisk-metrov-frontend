@include('filters.buttons-grid', [
    'id' => 'payment-methods-buttons',
    'buttonsTitle' => 'Способы оплаты',
    'elements' => $searchData->dropdownData->mortgages,
])
