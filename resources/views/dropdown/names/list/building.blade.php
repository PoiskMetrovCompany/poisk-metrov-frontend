@foreach ($searchData->names->values as $item)
    @include('dropdown.names.search-item', [
        'item' => $item,
        'context' => 'ЖК',
        'icon' => 'search-building',
        'field' => $searchData->names->field,
    ])
@endforeach
