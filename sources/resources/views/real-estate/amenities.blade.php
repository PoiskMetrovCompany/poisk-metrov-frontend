@if (isset($amenities) && count($amenities) > 0)
    <div class="amenities base-container">
        <h2 class="real-estate title">Об объекте</h2>
        <div class="amenities container">
            @foreach ($amenities as $item)
                <div class="amenities unit">
                    <img src="{{ Vite::asset('resources/assets/amenity.svg') }}">
                    <div>{{ $item->amenity }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif
