<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>{!! $title ?? 'Поиск метров' !!}</title>
    <link rel="icon" type="image/x-icon" href="/icon/16.ico">
    {{-- Чтобы Vite::content работал надо хотя бы раз сделать npm run build --}}
    {{-- Также сборка нужна чтобы с этот стиль загрузились новые данные --}}
    <style>
        {!! Vite::content('resources/scss/pdf-styles.scss') !!}
    </style>

    {{-- Для разработки с автообновлением стилей --}}
    {{-- @vite(['resources/scss/pdf-styles.scss']) --}}
    @include('scripts.yandex-maps-api')
    <script>
        let previews = {};
    </script>
    <script type="module">
        {!! Vite::content('resources/js/pdfExport/loadPresentationMap.js') !!}
    </script>
</head>

<body>
    @yield('content')
</body>

</html>
