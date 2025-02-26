@extends('document-layout', ['title' => $head_title])

@php
    $hasPresentation = false;
    if (isset($presentationLink)) {
        $hasPresentation = true;
    }

    $filesStr = implode(',', $gallery);
@endphp

@section('additional-meta')
    @isset($metaTags)
        @foreach ($metaTags as $metaNameTagPair)
            <meta name="{{ $metaNameTagPair->name }}" content="{{ $metaNameTagPair->content }}">
        @endforeach
    @endisset
@endsection

@section('pagescript')
    @vite('resources/js/realEstate/realEstate.js')
    <script>
        let pageType = 'real-estate';
    </script>
@endsection

@section('content')
    <div id="top-content" class="real-estate header-with-picture" code={{ $code }}>
        <div class="real-estate header main-page">
            <h1 id="building-name" class="real-estate main-title">{{ $h1 }}</h1>
            @include('common.share-page-button', ['id' => 'share-page-button-mobile'])
            <div class="real-estate info-card builder">
                <div>Застройщик</div>
                <div class="real-estate info-card highlighted">{{ $builder }}</div>
            </div>
            <div class="real-estate info-card min-price-container">
                <div>Минимальная цена</div>
                <div class="real-estate info-card min-price"> от {{ $minPriceDisplay }} </div>
            </div>
            <div class="real-estate location no-metro-mobile">
                <div class="real-estate metro-and-street">
                    <div>{{ $location->district }}, {{ $address }}</div>
                    @if (isset($metroDescription) && $metroDescription != '')
                        <div id="metro-container" class="location metro-stations">
                            <img src="{{ Vite::asset('resources/assets/metro/metro-red.svg') }}"
                                class="icon d24x24 transparent-background">
                            <span>{{ $metroDescription }} </span>
                        </div>
                    @else
                        <img src="{{ Vite::asset('resources/assets/metro/metro-red.svg') }}"
                            class="icon transparent-background" style="display: none">
                    @endif
                </div>
                @include('common.share-page-button', [
                    'id' => 'share-page-button',
                    'subclass' => 'hint-below',
                ])
            </div>
        </div>
        <div id={{ $code }}>
            <div class="real-estate background-image">
                @include('real-estate.gallery')
                @include('real-estate.info-card')
            </div>
        </div>
    </div>
    @include('real-estate.plans-filter')
    @include('home-page.mortgage')
    @include('real-estate.object-info')
    @include('real-estate.estate-description')
    @include('real-estate.infrastructure-map')
    @include('real-estate.amenities')
    @include('real-estate.panorama')
    @include('real-estate.building-progress')
    @include('real-estate.docs')
    @include('menus.forms.sign-up-for-building')
    @include('chart.price-chart')
@endsection
