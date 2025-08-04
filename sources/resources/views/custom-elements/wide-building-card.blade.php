@php
    $spritePositionsStr = json_encode($spritePositions);
    $metroMoveIcon = $metro_type == 'transport' ? 'car' : 'people';
@endphp

<wide-building-card id="{{ $code }}" city="{{ $location['code'] }}" long="{{ $longitude }}"
    lat="{{ $latitude }}" buildingname="{{ $name }}" metro="{{ $metro_station }}"
    metrominutes="{{ $metroMinutes }}" metromoveicon="{{ $metroMoveIcon }}">
    <div type="top-content">
        @include('custom-elements.image-gallery', [
            'spritePositionsStr' => $spritePositionsStr,
            'spriteUrl' => $spriteUrl,
        ])
        <div type="top-buttons">
            <div type="commission">Комиссия от 3%</div>
        </div>
        <div type="bottom-shadow"></div>
    </div>
    <section type="description-container">
        @php
            $buildDates = $earliestBuildDate;

            if ($earliestBuildDate != $latestBuildDate) {
                $buildDates = "$earliestBuildDate-$latestBuildDate";
            }
        @endphp
        <header>
            <h6>{{ $name }}
                <div type="group">
                    {{ $builder }}
                </div>
                <div type="description">
                    {{ $location['district'] }}, {{ $address }}
                </div>
            </h6>
            <div type="top-buttons">
                @include('custom-elements.button.like')
            </div>
            <div type="description">
                <span type="address">{{ $location['district'] }}, {{ $address }}</span>
                <div type="description" and="one-line">
                    @include('icons.icon', ['iconClass' => 'metro-red'])
                    <span> {{ $metro_station }} </span>
                    <span class="icon {{ $metroMoveIcon }}"></span>
                    <span> {{ $metroMinutes }} </span>
                </div>
            </div>
            <div type="description">
                <div>Сдача {{ $buildDates }}</div>
                <div type="sections">
                    @if ($sectionCount > 4)
                        {{ $sectionCount }} корпусов
                    @elseif($sectionCount > 1)
                        {{ $sectionCount }} корпуса
                    @else
                        {{ $sectionCount }} корпус
                    @endif
                </div>
            </div>
        </header>
        @include('common.divider')
        <ul type="info">
            @include('custom-elements.building-card.info-line', [
                'header' => 'Застройщик',
                'data' => $builder,
            ])
            @include('custom-elements.building-card.info-line', [
                'header' => 'Срок сдачи',
                'data' => $buildDates,
            ])
            @include('custom-elements.building-card.info-line', [
                'header' => 'Класс',
                'data' => $residentialComplexClass,
            ])
            {{-- TODO: fix apartment count --}}
            @include('custom-elements.building-card.info-line', [
                'header' => 'В продаже',
                'data' => "$plansCount квартир",
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
        <div>
            @include('buttons.link', [
                'buttonText' => 'Создать заявку',
                'link' => "/agent/client/register?residential_complex=$code",
                'subclass' => 'white',
            ])
            @include('buttons.link', [
                'buttonText' => "$plansCount предложений",
                'link' => "/$code",
            ])
        </div>
    </section>
</wide-building-card>
@include('custom-elements.agent-building-card', ['smallOnly' => true])
