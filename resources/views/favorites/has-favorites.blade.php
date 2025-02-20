@php
    $plansCount = 0;
    $buildingsCount = 0;

    if (isset($favoritePlans)) {
        $plansCount = count($favoritePlans);
    }

    if (isset($favoriteBuildings)) {
        $buildingsCount = count($favoriteBuildings);
    }

    $onlyBuildings =
        ($plansCount == 0 && $buildingsCount > 0) || Cookie::get('lastFavoriteSelected') == 'fullComparisonBuildings';

    $isCompactPlansView = Cookie::get('fullComparisonPlans') != 'true';
    $isCompactBuildingsView = Cookie::get('fullComparisonBuildings') != 'true';
@endphp

<script>
    let buildingsCount = {{ $buildingsCount }};
    let plansCount = {{ $plansCount }};
</script>

<div class="favorites container" id="favorites-with-items">
    <div class="favorites title">Избранное</div>
    <div class="favorites grid">
        <div class="favorites left-item">
            @include('favorites.category-menu')
            @include('favorites.registered', [
                'downloadButtonId' => 'download-favorite-presentation-button',
            ])
        </div>
        @include('favorites.plans')
        @include('favorites.buildings')
        @include('favorites.registered', [
            'subclass' => 'mobile',
            'buttonId' => 'favorites-leave-request-mobile',
            'downloadButtonId' => 'download-favorite-presentation-button-mobile',
        ])
    </div>
</div>
