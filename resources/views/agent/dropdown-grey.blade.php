<div class="agent dropdown-with-title">
    @isset($header)
        <legend>{{ $header }}</legend>
    @endisset
    @include('custom-elements.select', [
        'id' => $id,
        'options' => $items,
    ])
</div>
