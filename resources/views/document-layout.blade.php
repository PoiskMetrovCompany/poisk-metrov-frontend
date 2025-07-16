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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.4.10/SmoothScroll.min.js"
            integrity="sha256-huW7yWl7tNfP7lGk46XE+Sp0nCotjzYodhVKlwaNeco=" crossOrigin="anonymous"></script>

    <meta property="og:title" content="Поиск метров">
    <meta property="og:site_name" content="Поиск метров">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:description" content="Поиск метров  — бесплатный сервис бронирования новостроек">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://poisk-metrov.ru/">
    <meta property="og:image" content="/meta/image.jpg">

    <meta name="yandex-verification" content="e02510a91b55c5d2" />

    @yield('preload-images')
    @vite([
        'resources/scss/styles.scss',
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @yield('head')

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
