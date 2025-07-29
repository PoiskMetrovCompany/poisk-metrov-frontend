@php
    $spritePositionsStr = json_encode($spritePositions);
    $metroMoveIcon = $metro_type == 'transport' ? 'car' : 'people';
@endphp

<agent-building-card id="{{ $code }}" city="{{ $location['code'] }}" long="{{ $longitude }}"
    lat="{{ $latitude }}" buildingname="{{ $name }}" metro="{{ $metro_station }}"
    metrominutes="{{ $metroMinutes }}" metromoveiiicon="{{ $metroMoveIcon }}"
    @if (isset($smallOnly)) smallOnly="{{ $smallOnly }}" @endif>
    <div type="top-content">
        @include('custom-elements.image-gallery', [
            'spritePositionsStr' => $spritePositionsStr,
            'spriteUrl' => $spriteUrl,
        ])
        {{-- <a href="/{{ $code }}{{ $queryString }}"> </a> --}}
        <div type="map"></div>
        <div type="top-buttons">
            <div type="commission">Комиссия от 3%</div>
            @include('custom-elements.button.like')
            <button type="button">
                @include('icons.icon', ['iconClass' => 'map', 'iconColor' => 'black'])
                @include('common.hint', ['text' => 'Показать на карте'])
            </button>
        </div>
        <div type="bottom-shadow"></div>
    </div>
    <section type="description-container" showmore="false">
        <h6>{{ $name }}</h6>
        <header>
            <div type="description">{{ $location['district'] }}, {{ $address }}</div>
            <div type="description" and="one-line">
                @include('icons.icon', ['iconClass' => 'metro-red'])
                <span> {{ $metro_station }} </span>
                <span class="icon {{ $metroMoveIcon }}"></span>
                <span> {{ $metroMinutes }} </span>
            </div>
            <div type="description" and="date">
                @if ($earliestBuildDate == $latestBuildDate)
                    Сдача {{ $earliestBuildDate }}
                @else
                    Сдача {{ $earliestBuildDate }}-{{ $latestBuildDate }}
                @endif
            </div>
        </header>
        <ul type="info">
            @include('custom-elements.building-card.info-line', [
                'header' => 'Застройщик',
                'data' => $builder,
            ])
            @include('custom-elements.building-card.info-line', [
                'header' => 'Отделка',
                'data' => $renovations,
            ])
            @if ($lowestFloor == $highestFloor)
                @include('custom-elements.building-card.info-line', [
                    'header' => 'Этажность',
                    'data' => $lowestFloor,
                ])
            @else
                @include('custom-elements.building-card.info-line', [
                    'header' => 'Этажность',
                    'data' => "$lowestFloor-$highestFloor",
                ])
            @endif
            @include('custom-elements.building-card.info-line', [
                'header' => ' ',
                'data' => ' ',
            ])
            @include('custom-elements.building-card.info-line', [
                'header' => ' ',
                'data' => ' ',
            ])
        </ul>
        <ul type="specifics">
            @include('custom-elements.building-card.line', ['data' => $apartmentSpecifics, 'num' => 0])
            @include('custom-elements.building-card.line', ['data' => $apartmentSpecifics, 'num' => 1])
            @include('custom-elements.building-card.line', ['data' => $apartmentSpecifics, 'num' => 2])
            @include('custom-elements.building-card.line', ['data' => $apartmentSpecifics, 'num' => 3])
            @include('custom-elements.building-card.line', ['data' => $apartmentSpecifics, 'num' => 4])
        </ul>
        <div type="more">Подробнее</div>
        <div>
            <div type="minprice">от {{ $minPriceDisplay }}</div>
            <div type="description">от {{ $minPricePerMeterDisplay }}/м²</div>
        </div>
        @if (isset($smallOnly) && $smallOnly == true)
            @include('buttons.link', [
                'buttonText' => 'Создать заявку',
                'link' => "/agent/client/register?residential_complex=$code",
                'subclass' => 'white',
            ])
        @endif
        @include('buttons.link', [
            'buttonText' => "$plansCount предложений",
            'link' => "/$code",
        ])
    </section>
</agent-building-card>
