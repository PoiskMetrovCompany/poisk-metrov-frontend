@php
    $spritePositionsStr = json_encode($spritePositions);
    $metroMoveIcon = $metro_type == 'transport' ? 'car' : 'people';
@endphp

<building-card id="{{ $code }}" city="{{ $location['code'] }}" long="{{ $longitude }}" lat="{{ $latitude }}"
    buildingname="{{ $name }}" metro="{{ $metro_station }}" metrominutes="{{ $metroMinutes }}"
    metromoveicon="{{ $metroMoveIcon }}">
    <div type="top-content">
        @include('custom-elements.image-gallery', [
            'spritePositionsStr' => $spritePositionsStr,
            'spriteUrl' => $spriteUrl,
        ])
        {{-- <a href="/{{ $code }}{{ $queryString }}"> </a> --}}
        <div type="map"></div>
        <div type="top-buttons">
            @include('custom-elements.button.like')
            <button type="button">
                @include('icons.icon', ['iconClass' => 'map', 'iconColor' => 'black'])
                @include('common.hint', ['text' => 'Показать на карте'])
            </button>
            @include('custom-elements.button.share')
        </div>
        <div type="bottom-shadow"></div>
    </div>
    <section type="description-container">
        <h6>{{ $name }}</h6>
        <header>
            <div type="group">{{ $builder }}</div>
            <div type="description">{{ $location['district'] }}, {{ $address }}</div>
            <div type="description" and="one-line">
                @if ($metro_station != null && $metroMinutes != null)
                    @include('icons.icon', ['iconClass' => 'metro-red'])
                    <span> {{ $metro_station }} </span>
                    <span class="icon {{ $metroMoveIcon }}"></span>
                    <span> {{ $metroMinutes }} </span>
                @else
                    <span style="height: 24px; display: block;">&nbsp;</span>
                @endif
            </div>
        </header>
        @include('common.divider')
        <ul>
            @include('custom-elements.building-card.line', ['data' => $apartmentSpecifics, 'num' => 0])
            @include('custom-elements.building-card.line', ['data' => $apartmentSpecifics, 'num' => 1])
        </ul>
        <div type="more">Подробнее</div>
        <div type="additional-info">
            <ul>
                @include('custom-elements.building-card.line', ['data' => $apartmentSpecifics, 'num' => 2])
                @include('custom-elements.building-card.line', [
                    'data' => $apartmentSpecifics,
                    'num' => 3,
                ])
            </ul>
            @include('common.divider')
            <div type="subheader">
            {{-- TODO: move to function --}}
                @if ($plansCount > 4)
                    {{ $plansCount }} квартир
                @elseif($plansCount > 1)
                    {{ $plansCount }} квартиры
                @elseif($plansCount == 1)
                    {{ $plansCount }} квартира
                @elseif(!$plansCount)
                    Нет квартир
                @endif
            </div>
            @include('buttons.link', [
                'subclass' => 'bordered',
                'buttonText' => 'Подробнее',
                'link' => "/$code",
            ])
        </div>
    </section>
</building-card>
