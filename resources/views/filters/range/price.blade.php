@include('filters.range', [
    'min' => $searchData->cheapest,
    'max' => $searchData->most_expensive,
    'id' => 'price-min-max-range',
    'legend' => 'Стоимость, млн ₽',
])
