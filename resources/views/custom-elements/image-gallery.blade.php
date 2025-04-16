@php
    $queryString = Request::getQueryString();

    if ($queryString != '') {
        $queryString = "?$queryString";
    }
@endphp

<image-gallery ignore-hover spritepositions="{{ $spritePositionsStr }}" style="background-image: url({{ "/storage/$spriteUrl" }})">
    {{-- TODO: load start sprite position --}}
    <a type="card-area" href="/{{ $code }}{{ $queryString }}">
        @for ($i = 0; $i < 5 && $i < count($spritePositions); $i++)
            <div></div>
        @endfor
    </a>
    <div type="slider-indicators">
        @for ($i = 0; $i < 5 && $i < count($spritePositions); $i++)
            <div></div>
        @endfor
    </div>
</image-gallery>
