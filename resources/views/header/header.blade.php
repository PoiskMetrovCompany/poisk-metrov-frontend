<header id="top-bar" class="header top-bar common-padding">
    <div class="header top-bar main">
        <div class="header vertical-content">
            @include('header.city-selection')
            @include('header.small-links')
        </div>
        <div class="header vertical-content">
            <div class="header top-bar-half">
                @include('header.site-logo')
                @include('header.links')
            </div>
            <div class="header top-bar-half">
                @include('header.contacts')
                @include('header.current-city')
                @include('header.profile-buttons', [
                    'loginButtonId' => 'login-button',
                    'profileButtonId' => 'profile-button',
                    'logoutButtonId' => 'logout-button',
                ])
            </div>
        </div>
    </div>
</header>
