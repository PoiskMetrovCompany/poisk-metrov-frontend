@php
    $files = [$plan_URL];

    if ($floor_plan_url != null) {
        $files[] = $floor_plan_url;
    }

    $fileStr = implode(',', $files);
@endphp

<div id="top-content" class="real-estate header-with-picture" code={{ $code }}>
    <div class="real-estate header apartment">
        <h1 id="building-name" class="real-estate main-title">{{ $h1 }}</h1>
        <div class="real-estate location">
            <div class="real-estate metro-and-street">
                <div>{{ $location->district }}, {{ $address }}</div>
                <div id="metro-container" class="location metro-stations">
                    @isset($metro_station)
                        <img src="{{ Vite::asset('resources/assets/metro/metro-red.svg') }}"
                            class="icon d24x24 transparent-background">
                        <span>{{ $metro_station }}, {{ $metro_time }}
                            @if ($metro_time > 4)
                                минут
                            @else
                                минуты
                            @endif
                            {{ $metro_type == 'transport' ? 'транспортом' : 'пешком' }}
                        </span>
                    @else
                        <img src="{{ Vite::asset('resources/assets/metro/metro-red.svg') }}"
                            class="icon transparent-background" style="display: none">
                    @endisset
                </div>
            </div>
            @include('common.share-page-button', [
                'id' => 'share-apartment-button',
                'subclass' => 'hint-below',
            ])
        </div>
    </div>
    <div class="plan top-grid">
        <div id={{ $code }} class="plan image-container">
            <div id="background-{{ $code }}" class="plan image"
                style="background-image: url('{{ $plan_URL }}')">
            </div>
            <input type="hidden" value="{{ $fileStr }}" id="previews_{{ $code }}">
            <div class="card-area-container">
                @for ($i = 0; $i < 5 && $i < count($files); $i++)
                    <div class="card-area" id="area_{{ $code }}_{{ $i }}"></div>
                @endfor
            </div>
            <div class="building-cards card-bottom-grid">
                @for ($i = 0; $i < 5 && $i < count($files); $i++)
                    <div class="slider-indicator" id="indicator_{{ $code }}_{{ $i }}"></div>
                @endfor
            </div>
        </div>
        <div class="plan apartment-description">
            @vite('resources/js/realEstate/downloadPresentation.js')
            <div class="plan description-card">
                <div class="plan description-item">
                    Цена
                    <div class="plan price">{{ $displayPrice }}</div>
                </div>
                @include('common.price-change', ['idModifier' => $code])
                <div class="plan description-container">
                    @include('plan.description-item', [
                        'itemTitle' => 'Срок сдачи',
                        'itemValue' => "$ready_quarter кв. $built_year",
                        'checkValue' => $built_year,
                    ])
                    @include('plan.description-item', [
                        'itemTitle' => 'Корпус',
                        'itemValue' => "$building_section",
                        'checkValue' => $building_section,
                    ])
                    @include('plan.description-item', [
                        'itemTitle' => 'Отделка',
                        'itemValue' => "$renovation",
                        'checkValue' => $renovation,
                    ])
                    @include('plan.description-item', [
                        'itemTitle' => 'Этаж',
                        'itemValue' => "$floor из $floors_total",
                        'checkValue' => $floor,
                    ])
                    @include('plan.description-item', [
                        'itemTitle' => 'Номер квартиры',
                        'itemValue' => "$apartment_number",
                        'itemId' => 'apartment-number',
                        'checkValue' => $apartment_number,
                    ])
                    @include('plan.description-item', [
                        'itemTitle' => 'Высота потолков',
                        'itemValue' => "$ceiling_height м",
                        'checkValue' => $ceiling_height,
                    ])
                    @include('plan.description-item', [
                        'itemTitle' => 'Общая площадь',
                        'itemValue' => "$area м²",
                        'checkValue' => $area,
                    ])
                    @include('plan.description-item', [
                        'itemTitle' => 'Жилая площадь',
                        'itemValue' => "$living_space м²",
                        'checkValue' => $living_space,
                    ])
                </div>
                <a id="download-presentation-button" link="/get-apartment-presentation/{{ $offer_id }}"
                    href="javascript:void(0)" class="common-button">
                    Скачать презентацию
                </a>
                <a href="javascript:void(0)" id="reserve-button" class="common-button">Забронировать</a>
                <a href="javascript:void(0)" id="order-call" class="common-button">Записаться на просмотр</a>
            </div>
        </div>
    </div>
</div>
