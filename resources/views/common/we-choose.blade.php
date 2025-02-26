@php
    $quizDisabled = boolval(config('app.quiz_disabled'));
@endphp

@if (!$quizDisabled)
    <div id="we-choose-apartment" class="we-choose base-container">
        <div class="we-choose text-container">
            <div class="title">
                Подберём квартиру по вашим критериям!
            </div>
            <div class="we-choose description">
                Заполните анкету с параметрами недвижимости и получите индивидуальную подборку актуальных предложений
            </div>
        </div>
        <div id="quiz-popup-open" class="common-button">Начать</div>
    </div>
    @include('foreign.quiz')
@endif
