@php
    $spritePositionsStr = json_encode($spritePositions);

    $metroMoveIcon = $metro_type == 'transport' ? 'car' : 'people';
@endphp

<div id="{{ $code }}" city="{{ $location['code'] }}" class="expanded-building-cards container"
    usemap="#slider_map_{{ $code }}" long="{{ $longitude }}" lat="{{ $latitude }}"
    buildingname={{ $name }} metro="{{ $metro_station }}" metrominutes="{{ $metroMinutes }}"
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
            @include('common.share-page-button', [
                'id' => "share-real-estate-button-$code",
            ])
        </div>
        <a href="/{{ $code }}" class="card-area-container">
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
        @include('favorites.deleted', ['title' => 'Вы удалили этот ЖК из Избранного'])
    </div>
    <div @auth class="expanded-building-cards description-container" @endauth
        @guest class="expanded-building-cards description-container blur" @endguest>
        <div class="building-cards description-grid">
            <div class="expanded-plan-card details">
                @include('favorites.description-line', [
                    'descriptionLineName' => 'Название ЖК',
                    'descriptionLineValue' => $name,
                ])
                @include('favorites.description-line', [
                    'descriptionLineName' => 'Застройщик',
                    'descriptionLineValue' => $builder,
                ])
                @include('favorites.description-line', [
                    'descriptionLineName' => 'Район',
                    'descriptionLineValue' => $location['district'],
                ])
                @include('favorites.description-line', [
                    'descriptionLineName' => 'Улица',
                    'descriptionLineValue' => $address,
                ])
                @include('favorites.description-line', [
                    'descriptionLineName' => 'Расстояние до метро',
                    'descriptionLineValue' => $metroDescription,
                ])
                @include('favorites.description-line', [
                    'descriptionLineName' => 'Срок сдачи',
                    'descriptionLineValue' => "{$earliestBuildDate} - {$latestBuildDate}",
                ])
                @include('favorites.description-line', [
                    'descriptionLineName' => 'Отделка',
                    'descriptionLineValue' => $renovations,
                ])
                @include('favorites.description-line', [
                    'descriptionLineName' => 'Тип дома',
                    'descriptionLineValue' => $materials,
                ])
                @include('favorites.description-line', [
                    'descriptionLineName' => 'Виды ипотеки',
                    'descriptionLineValue' => $mortgages,
                ])
            </div>
            <a href="/{{ $code }}" class="common-button bordered">
                Подробнее
            </a>
        </div>
    </div>
</div>
