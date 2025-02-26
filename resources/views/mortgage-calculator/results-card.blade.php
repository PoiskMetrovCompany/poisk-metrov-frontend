<div class="mortgage-calculator info container">
    <div class="mortgage-calculator info header">
        <h6>Предварительный расчет</h6>
        <div id="choose-mortgage-program-hint">
            <span>Выберите ипотечную программу для расчета</span>
        </div>
        <div>
            <span>Сумма кредита: </span>
            <span id="mortgage-display">-</span>
        </div>
    </div>
    <div class="mortgage-calculator info grid">
        @include('mortgage-calculator.info-unit', [
            'id' => 'monthly-payment',
            'title' => 'Платеж в месяц',
            'iconType' => 'calendar',
        ])
        @include('mortgage-calculator.info-unit', [
            'id' => 'required-income',
            'title' => 'Необходимый доход',
            'iconType' => 'wallet',
        ])
    </div>
    @include('buttons.common', [
        'buttonId' => 'learn-more-mortgage',
        'buttonText' => 'Заявка на консультацию',
    ])
</div>
