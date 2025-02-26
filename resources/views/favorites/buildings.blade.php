<div id="complexes-grid" class="favorites complexes-grid" style="display: {{ $onlyBuildings ? 'grid' : 'none' }}">
    <div class="favorites buttons">
        @include('favorites.sorting', [
            'id' => 'favorites-sorting-buildings-dropdown',
            'items' => [
                'Сначала дешевле',
                'Сначала дороже',
                'Сначала с меньшей площадью',
                'Сначала с большей площадью',
            ],
        ])
        @include('favorites.compare', [
            'id' => 'favorites-compare-buildings-button',
            'isActive' => !$isCompactBuildingsView,
        ])
        @include('favorites.show-on-map', ['id' => 'favorites-show-buildings-map-button'])
    </div>
    @if ($isCompactBuildingsView)
        <div class="building-cards grid compact" id="complexes-gallery-compact"
            @if ($buildingsCount == 0) style="display: none" @endif>
            @foreach ($favoriteBuildings as $building)
                @include('building-card.card', $building)
            @endforeach
        </div>
    @else
        <div class="favorites plans-grid-compare">
            <div class="favorites slider-buttons" id="complexes-gallery-buttons">
                <div class="favorites slider-button left disabled">
                    <div class="icon arrow-left d24x24"></div>
                </div>
                <div class="favorites slider-button right orange">
                    <div class="icon arrow-right d24x24"></div>
                </div>
            </div>
            <div class="favorites favorites-grid" id="complexes-gallery"
                @if ($buildingsCount == 0) style="display: none" @endif>
                @foreach ($favoriteBuildings as $building)
                    @include('favorites.expanded-building-card', $building)
                @endforeach
            </div>
        </div>
    @endif
    @guest
        @if ($buildingsCount > 0 && !$isCompactBuildingsView)
            @include('favorites.want-compare', [
                'id' => 'favorites-login-building-button',
                'title' => 'Хотите сравнить избранные ЖК?',
            ])
        @endif
    @endguest
    @if ($buildingsCount == 0)
        <div class="favorites title">У вас пока нет избранных жилых комплексов</div>
    @endif
</div>
