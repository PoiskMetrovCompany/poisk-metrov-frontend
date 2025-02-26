@extends('document-layout', ['title' => $head_title])

@section('additional-meta')
    @isset($metaTags)
        @foreach ($metaTags as $metaNameTagPair)
            <meta name="{{ $metaNameTagPair->name }}" content="{{ $metaNameTagPair->content }}">
        @endforeach
    @endisset
@endsection

@section('pagescript')
    @vite('resources/js/gallery/plansLoader.js')
    @vite('resources/js/plan/plan.js')
    <script>
        let pageType = 'plan';
        let planData = {
            price: {{ $price }}
        }
    </script>
@endsection

@section('content')
    @include('plan.header-with-picture')
    @include('home-page.mortgage')
    @include('real-estate.object-info')
    @include('real-estate.estate-description')
    @include('real-estate.infrastructure-map')
    @include('real-estate.amenities')
    @include('real-estate.panorama')
    @include('real-estate.building-progress')
    @include('real-estate.docs')
    @include('menus.forms.reserve-apartment')
    @include('menus.forms.sign-up-for-building')
    @include('chart.price-chart', ['historicApartmentPrice' => $displayPrice])
    @include('plan.similar')
@endsection
