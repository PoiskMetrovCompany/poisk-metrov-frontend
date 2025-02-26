@php
    $firstGalleryImage = $gallery[0];

    if (!str_contains($firstGalleryImage, 'http')) {
        $firstGalleryImage = '/' . $gallery[0];
    }
@endphp
<div id="background-{{ $code }}" class="real-estate gallery-container"
    style="background-image: url('{{ $firstGalleryImage }}')">
    <input type="hidden" value="{{ $filesStr }}" id="previews_{{ $code }}">
    <div class="card-area-container">
        @for ($i = 0; $i < count($gallery) && ($i < 5 || str_contains($gallery[$i], '00000000')); $i++)
            <div class="card-area" id="area_{{ $code }}_{{ $i }}"></div>
        @endfor
    </div>
    <div class="building-cards card-bottom-grid real-estate-buttons">
        @for ($i = 0; $i < count($gallery) && ($i < 5 || str_contains($gallery[$i], '00000000')); $i++)
            <div class="slider-indicator" id="indicator_{{ $code }}_{{ $i }}"></div>
        @endfor
    </div>
</div>
