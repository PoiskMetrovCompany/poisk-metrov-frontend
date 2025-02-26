@foreach ($searchData->builders->values as $item)
    @include('dropdown.names.search-item', [
        'item' => $item,
        'context' => 'Застройщик',
        'icon' => 'search-builder',
        'field' => $searchData->builders->field,
    ])
@endforeach
