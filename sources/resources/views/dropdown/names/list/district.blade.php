@foreach ($searchData->districts->values as $item)
    @include('dropdown.names.search-item', [
        'item' => $item,
        'context' => 'Район',
        'icon' => 'search-location',
        'field' => $searchData->districts->field,
    ])
@endforeach
