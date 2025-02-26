<div class="mortgage-card base-container">
    @vite('resources/js/homePage/mortgageTimer.js')
    <h2>Следующее заседание Совета директоров ЦБ</h2>
    <div class="mortgage-card timer-section">
        <div class="mortgage-card timer-section-title">Изменение процентных ставок</div>
        <div class="mortgage-card timer-card">
            <div class="mortgage-card time">
                -
            </div>
            <div class="mortgage-card time">
                :
            </div>
            <div class="mortgage-card time">
                -
            </div>
            <div class="mortgage-card time">
                :
            </div>
            <div class="mortgage-card time">
                -
            </div>
            <div class="mortgage-card time-description">
                дней
            </div>
            <div class="mortgage-card time-description">

            </div>
            <div class="mortgage-card time-description">
                часов
            </div>
            <div class="mortgage-card time-description">

            </div>
            <div class="mortgage-card time-description">
                минут
            </div>
        </div>
    </div>
    @include('buttons.link', [
        'buttonText' => 'Подробнее',
        'subclass' => 'white',
        'link' => '/mortgage',
    ])
</div>
