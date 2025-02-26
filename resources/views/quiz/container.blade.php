<div>
    @vite('resources/js/quiz/loader.js')
    <div id="quiz-section" class="quiz base-container">
        @include('quiz.step', [
            'quizTitleHtml' => 'КАКОЙ <span>ЕЖЕМЕСЯЧНЫЙ</br>ПЛАТЁЖ</span> ПО ИПОТЕКЕ</br>ВАМ ПОДОЙДЁТ?',
            'buttonTexts' => [
                'До 30 тыс. ₽',
                'До 50 тыс. ₽',
                'До 70 тыс. ₽',
                'До 100 тыс. ₽',
                'Больше 100 тыс. ₽',
            ],
            'isVisible' => true,
        ])
        @include('quiz.step', [
            'quizTitleHtml' => 'КАКОЙ БУДЕТ</br> <span>ПЕРВОНАЧАЛЬНЫЙ</br>ВЗНОС?</span>',
            'buttonTexts' => [
                'Не знаю',
                'Без взноса',
                'До 1 млн. ₽',
                'До 3 млн. ₽',
                'До 5 млн. ₽',
                'Более 5 млн. ₽',
            ],
        ])
        @include('quiz.step', [
            'quizTitleHtml' => '<span>СКОЛЬКО КОМНАТ</span></br>БУДЕТ В КВАРТИРЕ?</br>',
            'buttonTexts' => ['Студия', '1 комната', '2 комнаты', '3 комнаты', '4+ комнаты'],
        ])
        @include('quiz.step', [
            'quizTitleHtml' => '<span>ОСТАВЬТЕ НОМЕР,</span></br>МЫ БЕСПЛАТНО</br>НАЙДЕМ ВАМ КВАРТИРУ',
        ])
    </div>
</div>
