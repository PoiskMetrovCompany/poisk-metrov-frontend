@extends('document-layout', [
    'title' => 'Избранное',
    'excludeNoIndexing' => true,
])

@section('pagescript')
    <script>
        let buildingsFromFavoritePlans = {!! json_encode($buildingsFromFavoritePlans) !!}
        let favoriteBuildings = {!! json_encode($favoriteBuildings) !!}
    </script>
    @vite('resources/js/favorites/favorites.js')
@endsection

@php
    $favCount = $favoritesService->countFavorites();
@endphp

@section('content')
    @include('fullscreenmap.map', ['id' => 'favorite-plans-map'])
    @include('fullscreenmap.map', ['id' => 'favorite-buildings-map'])
    <div class="favorites base-container">
        @if ($favCount == 0)
            @include('favorites.no-favorites')
        @else
            @include('favorites.has-favorites')
        @endif
    </div>
@endsection
