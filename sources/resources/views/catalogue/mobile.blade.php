<div class="portable-filter base-container">
    @if (!isset($buttonsOnly) || $buttonsOnly == false)
        <div>
            <span class="link-highlight">Поиск Метров</span><span> — бесплатный сервис бронирования новостроек</span>
        </div>
        <div id="learn-about-first-sale-menu-button" class="learn-sale">
            <div class="learn-sale text">УЗНАТЬ О СТАРТЕ ПРОДАЖ ПЕРВЫМ</div>
            <img src="{{ Vite::asset('resources/assets/space.svg') }}" class="space">
            <img src="{{ Vite::asset('resources/assets/arrows/arrow.svg') }}">
        </div>
    @endif
    @include('buttons.bordered', [
        'buttonId' => 'show-filters-menu-mobile',
        'buttonIcon' => 'filters-button',
        'buttonText' => 'Фильтры',
    ])
    @include('buttons.bordered', [
        'buttonId' => 'catalogue-map-button-mobile',
        'buttonIcon' => 'paper-map',
        'buttonText' => 'На карте',
    ])
</div>
