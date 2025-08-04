@php
    $spritePositionsStr = json_encode($spritePositions);

    $metroMoveIcon = $metro_type == 'transport' ? 'car' : 'people';
@endphp

<div id="{{ $code }}" city="{{ $location['code'] }}" class="building-cards container"
    usemap="#slider_map_{{ $code }}" long="{{ $longitude }}" lat="{{ $latitude }}"
    buildingname="{{ $name }}" metro="{{ $metro_station }}" metrominutes="{{ $metroMinutes }}"
    metromoveicon="{{ $metroMoveIcon }}">
    <div id="background-{{ $code }}" style="background-image: url({{ '/storage/' . $spriteUrl }})"
        class="building-cards card">
        <input type="hidden" value="{{ $spritePositionsStr }}" id="previews_{{ $code }}">
        <div class="building-cards card-upper-grid" id="top_grid_{{ $code }}">
            <div id="{{ $code }}-like-button" class="building-cards card-button {!! $isFavorite ? 'orange' : '' !!}">
                <div class="icon action-like p50x50 grey5"></div>
                @include('common.hint', ['text' => 'Добавить в избранное'])
            </div>
            <div id="{{ $code }}-map-button" class="building-cards card-button">
                <div class="icon map p50x50 grey5"></div>
                @include('common.hint', ['text' => 'Показать на карте'])
            </div>
            @include('common.share-page-button', ['id' => "share-real-estate-button-$code"])
        </div>
        @php
            $queryString = Request::getQueryString();

            if ($queryString != '') {
                $queryString = "?$queryString";
            }
        @endphp
        <a href="/{{ $code }}{{ $queryString }}" class="card-area-container">
            @for ($i = 0; $i < 5 && $i < count($spritePositions); $i++)
                <div class="card-area" id="area_{{ $code }}_{{ $i }}"></div>
            @endfor
        </a>
        <div id="{{ $code }}-map" class="building-cards map"></div>
        <div class="building-cards bottom-shadow"></div>
        <div class="building-cards card-bottom-grid">
            @for ($i = 0; $i < 5 && $i < count($spritePositions); $i++)
                <div class="slider-indicator" id="indicator_{{ $code }}_{{ $i }}"></div>
            @endfor
        </div>
    </div>
    <div id="full-description-{{ $code }}" class="building-cards description-container">
        <div class="building-cards name">{{ $name }}</div>
        <div class="building-cards header-info">
            <div class="building-cards group">{{ $builder }}</div>
            <div class="building-cards description">{{ $location['district'] }}, {{ $address }}</div>
            <div class="building-cards description one-line">
                @if ($metro_station != null && $metroMinutes != null)
                    <img src="{{ Vite::asset('resources/assets/metro/metro-red.svg') }}"
                        class="icon transparent-background">
                    <span> {{ $metro_station }} </span>
                    <span class="icon d24x24 {{ $metroMoveIcon }} orange"></span>
                    <span> {{ $metroMinutes }} </span>
                @else
                    <span style="height: 24px; display: block;">&nbsp;</span>
                @endif
            </div>
        </div>
        <div class="divider"> </div>
        <div class="building-cards description-grid">
            @include('building-card.card-line', ['data' => $apartmentSpecifics, 'num' => 0])
            @include('building-card.card-line', ['data' => $apartmentSpecifics, 'num' => 1])
        </div>
        <div id="more_{{ $code }}" class="building-cards more">Подробнее</div>
        <div id="additional-description-{{ $code }}" class="building-cards additional-info">
            <div class="building-cards description-grid">
                @include('building-card.card-line', ['data' => $apartmentSpecifics, 'num' => 2])
                @include('building-card.card-line', ['data' => $apartmentSpecifics, 'num' => 3])
            </div>
            <div class="divider"></div>
            @if ($plansCount > 4)
                <div class="building-cards title-section">{{ $plansCount }} квартир</div>
            @else
                <div class="building-cards title-section">{{ $plansCount }} квартиры</div>
            @endif
            <a href="/{{ $code }}" class="common-button bordered">
                Подробнее
            </a>
        </div>
    </div>
</div>
