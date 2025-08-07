@foreach ($searchData->stations->values as $item)
    @include('dropdown.names.search-item', [
        'item' => $item,
        'context' => 'Метро',
        'icon' => 'search-metro',
        'field' => $searchData->stations->field,
    ])
@endforeach
