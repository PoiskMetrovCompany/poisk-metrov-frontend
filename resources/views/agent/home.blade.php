@extends('document-layout', ['title' => 'Агентский кабинет'])

@section('pagescript')
    @vite('resources/js/agent/home-page.js')
    <script>
        let allItemsInCity = {!! json_encode(
            array_values(
                $cachingService->getCards($residentialComplexRepository->getCatalogueForCity($selectedCity)->pluck('code')->toArray()),
            ),
        ) !!}
    </script>
@endsection

@section('content')
    @include('fullscreenmap.map', ['id' => 'best-offers-map', 'showCatalogueButton' => 'true'])
    @include('home-page.search')
    @include('agent.home.reservations')
    @include('agent.home.announcements')
    @include('common.new-best-offers')
    @include('agent.home.categories')
    @include('home-page.news')
@endsection
