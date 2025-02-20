<div class="agent dropdown-with-title">
    @isset($header)
        <legend>{{ $header }}</legend>
    @endisset
    @include('custom-elements.date-picker', [
        'id' => 'show-date-calendar',
    ])
</div>
