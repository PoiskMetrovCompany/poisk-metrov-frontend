@php
    $whereCity = $cityService->where[$selectedCity];
@endphp

<header class="agent search header">
    <h1>Каталог недвижимости в {{ $whereCity }}
    </h1>
    <div type="mapbutton" id="agent-catalogue-map-button">
        <div type="mapbuttonshadow"></div>
        @include('buttons.common', [
            'buttonIcon' => 'paper-map',
            'buttonText' => 'На карте',
            //'subclass' => 'superwhite',
            'subclass' => 'white',
        ])
    </div>
    @php
        $searchData = json_decode($searchService->getSearchData());
        $dropdownData = $searchData->dropdownData;
    @endphp
    <div id="agent-catalogue-recommendation-buttons" type="buttongrid" showall="0">
        @include('agent.search.notable-data-button', [
            'text' => 'Срок сдачи в 2025',
            'count' => $apartmentRepository->countApartmentsWithParameter('built_year', 2025),
            'dropdown' => $dropdownData->years->data,
            'dropdownKey' => '2025',
        ])
        @include('agent.search.notable-data-button', [
            'text' => 'Срок сдачи в 2026',
            'count' => $apartmentRepository->countApartmentsWithParameter('built_year', 2026),
            'dropdown' => $dropdownData->years->data,
            'dropdownKey' => '2026',
        ])
        @include('agent.search.notable-data-button', [
            'text' => 'В кирпичном доме',
            'count' => $apartmentRepository->countApartmentsWithParameter('building_materials', 'кирпичный'),
            'dropdown' => $dropdownData->materials->data,
            'dropdownKey' => 'кирпичный',
        ])
        <button type="button" more="true">
            ...
        </button>
        @include('agent.search.notable-data-button', [
            'text' => 'В панельном доме',
            'count' => $apartmentRepository->countApartmentsWithParameter('building_materials', 'панельный'),
            'dropdown' => $dropdownData->materials->data,
            'dropdownKey' => 'панельный',
        ])
        {{-- @include('agent.search.notable-data-button', [
            'text' => 'Без отделки',
            'count' => 123,
            'dropdown' => $dropdownData->finishing->data,
            'dropdownKey' => 1,
        ]) --}}
        @include('agent.search.notable-data-button', [
            'text' => 'Под ключ',
            'count' => $apartmentRepository->countApartmentsWithParameter('renovation', 'Отделка "под ключ"'),
            'dropdown' => $dropdownData->finishing->data,
            'dropdownKey' => 'Отделка "под ключ"',
        ])
        @include('agent.search.notable-data-button', [
            'text' => 'С черновой отделкой',
            'count' => $apartmentRepository->countApartmentsWithParameter('renovation', 'Черновая отделка'),
            'dropdown' => $dropdownData->finishing->data,
            'dropdownKey' => 'Черновая отделка',
        ])
        @include('agent.search.notable-data-button', [
            'text' => 'Свернуть',
        ])
    </div>
</header>
