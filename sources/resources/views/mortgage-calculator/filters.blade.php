<div class="mortgage-calculator selection filters-container">
    @include('mortgage-calculator.slider-input', [
        'legend' => 'Стоимость квартиры, ₽',
        'textInputId' => 'current-mortgage-price',
        'maxValueDisplay' => 'max-mortgage-price',
        'sliderId' => 'mortgage-price-slider',
    ])
    @include('mortgage-calculator.slider-input', [
        'legend' => 'Первоначальный взнос, ₽',
        'textInputId' => 'current-fee',
        'maxValueDisplay' => 'max-start-fee',
        'sliderId' => 'mortgage-start-fee-slider',
    ])
    @include('mortgage-calculator.slider-input', [
        'legend' => 'Срок кредита, лет',
        'textInputId' => 'current-mortgage-term',
        'maxValueDisplay' => 'max-mortage-term',
        'sliderId' => 'mortgage-year-slider',
    ])
    @include('catalogue.dropdown', [
        'id' => 'bank',
        'title' => '',
        'preview' => 'Банк',
        'hideCounter' => false,
        'options' => $bankService->getBankDropdownData(),
        'optionsTemplate' => 'dropdown.options.catalogue',
    ])
</div>
