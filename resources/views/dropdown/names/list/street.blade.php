@foreach ($searchData->addresses->values as $item)
    @include('dropdown.names.search-item', [
        'item' => $item,
        'context' => 'Улица',
        'icon' => 'search-street',
        'field' => $searchData->addresses->field,
    ])
@endforeach
