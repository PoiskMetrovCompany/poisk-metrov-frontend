<div class="real-estate info-card base-container">
    @vite('resources/js/realEstate/downloadPresentation.js')
    <div class="real-estate info-card min-price-container">
        <div>Минимальная цена</div>
        <div class="real-estate info-card min-price"> от {{ $minPriceDisplay }} </div>
    </div>
    @include('common.price-change', ['idModifier' => $code])
    <div class="real-estate info-card builder">
        <div>Застройщик</div>
        <div class="real-estate info-card highlighted">{{ $builder }}</div>
    </div>
    <div class="real-estate info-card display">
        @include('real-estate.info-card.line', [
            'title' => 'Срок сдачи',
            'value' => $builtYear,
        ])
        @include('real-estate.info-card.line', [
            'title' => 'Отделка',
            'value' => $renovation,
        ])
        @include('real-estate.info-card.line', [
            'title' => 'Тип дома',
            'value' => $buildingMaterials,
        ])
        @include('real-estate.info-card.line', [
            'title' => 'Этажность',
            'value' => $floorsTotal,
        ])
    </div>
    <div class="real-estate info-card bottom-buttons">
        <a id="download-presentation-button" href="javascript:void(0)"
            link="/get-building-presentation/{{ $code }}" class="common-button">
            Скачать презентацию
        </a>
        <a id="order-call-mobile" class="common-button">Записаться на просмотр</a>
    </div>
</div>
