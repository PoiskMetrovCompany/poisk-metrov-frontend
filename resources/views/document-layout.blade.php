<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @yield('additional-meta')
    @if (!isset($excludeNoIndexing) || !$excludeNoIndexing)
        @include('technical.no-indexing')
    @endif

    <title>{!! $title ?? 'Поиск метров' !!}</title>
    <link rel="icon" type="image/x-icon" href="/icon/16.ico">
    <link rel="preconnect"
        href="https://yastatic.net/s3/front-maps-static/maps-front-jsapi-3/3.0.13965858/build/static/bundles/main.js" />
    <link rel="preconnect"
        href="https://yastatic.net/s3/front-maps-static/maps-front-jsapi-3/3.0.13965858/build/static/bundles/vector.js" />
    <link rel="preconnect" href="https://mc.yandex.ru/metrika/tag.js" />
    <meta name="yandex-verification" content="e02510a91b55c5d2" />

    @yield('preload-images')
    @vite([
        'resources/scss/styles.scss',
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @if (App::isProduction())
        @include('scripts.clarity')
    @endif

    @include('scripts.crm-chat')
    @include('scripts.yandex-maps-api')
    @include('scripts.user-auth-js-variable')
    @include('scripts.current-city')
    @include('scripts.pusher')
    @yield('pagescript')
</head>

<body>
    @include('common.yandex-metrika')
    <div class="header-ancor" id="page-top"></div>
    @include('header.header')
    <div id="temp-map-container" class="building-cards temp-map-container"></div>
    <div id="new-temp-map-container" class="building-cards temp-map-container"></div>
    <main>
        @yield('content')
        @include('menus.forms.consult-request')
    </main>
    @include('common.bottom-buttons')
    @include('chat.window')
    @include('authorization.popups')
    @include('popups.thanks-for-contacts')
    @include('common.loader')
    @include('footer.footer')
    @include('shared.toolbar')
    @include('shared.mobile-menu')
    @include('menus.forms.revert-ads-agreement')
    @include('popups.ads-agreement-reverted')
    @include('custom-elements.user-menu')
    @include('menus.city-selection')
</body>

</html>
