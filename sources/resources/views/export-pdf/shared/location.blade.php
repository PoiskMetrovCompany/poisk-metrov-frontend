<div class="page">
    @include('export-pdf.shared.parts.header')
    @include('export-pdf.shared.parts.divider')
    <div class="content-container">
        <div class="complexes-title-container">
            <div class="complex-title">Расположение комплекса</div>
            <div class="map" previewlink="data:image/png;base64,{{ $preview }}"
                longitude="{{ $complex['longitude'] }}" latitude="{{ $complex['latitude'] }}" zoom="16"
                infrastructure="{{ $complex['infrastructure'] }}" id="presentation-map-{{ $complex['code'] }}">
            </div>
        </div>
        @if (count($complex['amenities']))
            <div class="amenities">
                Преимущества
                <div class="amenities-list">
                    @foreach ($complex['amenities'] as $amenity)
                        <div class="amenity">
                            <img src="{{ Vite::asset('resources/assets/amenity.svg') }}"
                                class="complexes-page amenity-icon">
                            {{ $amenity->amenity }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
