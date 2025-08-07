<mobile-toolbar>
    @include('shared.toolbar.button', [
        'icon' => 'home',
        'title' => 'Главная',
        'link' => '/',
        'isCurrentPage' => in_array(Request::path(), $cityService->possibleCityCodes),
    ])
    @include('shared.toolbar.button', [
        'icon' => 'buildings',
        'title' => 'Новостройки',
        'link' => '/catalogue',
        'isCurrentPage' => Request::is('*/catalogue'),
    ])
    @if (Request::is('agent*'))
        @include('shared.toolbar.button', [
            'icon' => 'new-request',
            'title' => 'Заявка',
            'link' => '/agent/client/register',
            'isCurrentPage' => Request::is('agent/client/register'),
        ])
        @include('shared.toolbar.button', [
            'icon' => 'action-like',
            'title' => 'Избранное',
            'link' => '/favorites',
            'isCurrentPage' => Request::is('favorites'),
        ])
    @endif
    @include('shared.toolbar.button', [
        'icon' => 'burger',
        'title' => 'Меню',
        'id' => 'mobile-menu-open',
        'isCurrentPage' => false,
    ])
</mobile-toolbar>
