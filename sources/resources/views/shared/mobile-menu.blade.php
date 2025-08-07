<mobile-menu id="mobile-menu" class="mobile-menu menu-container">
    <div class="mobile-menu container">
        <div class="mobile-menu header">
            <div class="mobile-menu topbar">
                <div class="mobile-menu half">
                    @include('header.site-logo')
                </div>
                <div class="header top-bar-half">
                    @include('header.contacts')
                    @include('header.profile-buttons', [
                        'loginButtonId' => 'login-button-mobile',
                        'profileButtonId' => 'profile-button-mobile',
                        'logoutButtonId' => 'logout-button-mobile',
                    ])
                </div>
            </div>
        </div>
        <div class="mobile-menu item">
            <div class="mobile-menu city-select">
                <div class="mobile-menu city-select item">
                    <div class="icon place orange d20x20"></div>
                    <div>{{ $cityName }}</div>
                    <div class="icon arrow-tailless grey5"></div>
                </div>
                @foreach ($otherCities as $otherCity)
                    @include('shared.mobile-menu.button.city', [
                        'cityCode' => $otherCity,
                        'cityName' => $cityService->cityCodes[$otherCity],
                    ])
                @endforeach
            </div>
        </div>
        @include('shared.mobile-menu.item', [
            'link' => 'sell',
            'text' => 'Продать',
        ])
        @include('shared.mobile-menu.item', [
            'link' => 'catalogue',
            'text' => 'Каталог недвижимости',
        ])
        @include('shared.mobile-menu.item', [
            'link' => 'about-us',
            'text' => 'О компании',
        ])
        @include('shared.mobile-menu.item', [
            'link' => 'mortgage',
            'text' => 'Ипотека',
        ])
        @include('shared.mobile-menu.item', [
            'link' => 'for-partners',
            'text' => 'Партнёрам',
        ])
        @include('shared.mobile-menu.item', [
            'link' => 'offices',
            'text' => 'Офисы',
        ])
    </div>
</mobile-menu>
