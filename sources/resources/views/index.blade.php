@extends('document-layout', [
    'title' => 'Поиск&nbsp;метров',
])

@section('preload-images')
    @foreach ($preloadService->preloadFolder('assets/partners') as $file)
        <link rel="preload" as="image" href={{ Vite::asset($file) }}>
    @endforeach
@endsection

@section('pagescript')
    <script>
        let allItemsInCity = {!! json_encode($allItemsInCity) !!}
    </script>
@endsection

@section('content')
    @include('home-page.top-cards')
    @include('home-page.search')
    @include('fullscreenmap.map', ['id' => 'best-offers-map', 'showCatalogueButton' => 'true'])
    @include('common.best-offers')
    @include('quiz.container')
    @include('home-page.apartment-suggestions')
    @include('home-page.mortgage')
    @include('home-page.news')
    @include('home-page.how-we-work')
    @include('home-page.partners')
    @include('home-page.reviews')
    @include('home-page.about-us')
    @include('menus.forms.download-catalogue')
    @include('menus.forms.learn-about-first-sale')
    @include('common.leave-request')
    @include('common.we-choose')
    @include('menus.forms.learn-mortgage')
@endsection
