<div id="plans-grid" class="favorites plans-grid" style="display: {{ !$onlyBuildings ? 'grid' : 'none' }}">
    <div class="favorites buttons">
        @include('favorites.sorting', [
            'id' => 'favorites-sorting-plans-dropdown',
            'items' => [
                'Сначала дешевле',
                'Сначала дороже',
                'Сначала с меньшей площадью',
                'Сначала с большей площадью',
            ],
        ])
        @include('favorites.compare', [
            'id' => 'favorites-compare-plans-button',
            'isActive' => !$isCompactPlansView,
        ])
        @include('favorites.show-on-map', ['id' => 'favorites-show-plans-map-button'])
    </div>
    @if ($isCompactPlansView)
        <div id="plans-gallery-compact" class="plans-filter apartment-dropdown card-grid compact"
            @if ($plansCount == 0) style="display: none" @endif>
            @foreach ($favoritePlans as $plan)
                @include('cards.plan.card', [
                    'name' => '',
                    'offerId' => $plan['offer_id'],
                    'planUrl' => $plan['plan_URL'],
                    'formattedPrice' => $plan['displayPrice'],
                    'type' => $plan['apartment_type'],
                    'area' => $plan['area'],
                    'floor' => $plan['floor'],
                    'maxFloor' => $plan['floors_total'],
                    'quarter' => $plan['ready_quarter'],
                    'builtYear' => $plan['built_year'],
                    'material' => $plan['building_materials'],
                    'finishing' => $plan['renovation'],
                    'isFavoriteApartment' => $plan['isFavoriteApartment'],
                    'priceDifference' => $plan['priceDifference'],
                    'hidePriceChangeButtonText' => true,
                ])
                @include('chart.price-chart', [
                    'historicApartmentPrice' => $plan['displayPrice'],
                    'code' => $plan['offer_id'],
                    'apartment_type' => $plan['apartment_type'],
                    'area' => $plan['area'],
                    'history' => $plan['history'],
                    'firstDate' => $plan['firstDate'],
                    'lastDate' => $plan['lastDate'],
                    'lastChanges' => $plan['lastChanges'],
                ])
            @endforeach
        </div>
    @else
        <div class="favorites plans-grid-compare">
            <div class="favorites slider-buttons" id="plans-gallery-buttons">
                <div class="favorites slider-button left disabled">
                    <div class="icon arrow-left d24x24"></div>
                </div>
                <div class="favorites slider-button right orange">
                    <div class="icon arrow-right d24x24"></div>
                </div>
            </div>
            <div class="favorites favorites-grid" id="plans-gallery"
                @if ($plansCount == 0) style="display: none" @endif>
                @foreach ($favoritePlans as $plan)
                    @include('favorites.expanded-plan-card', $plan)
                @endforeach
            </div>
        </div>
    @endif
    @guest
        @if ($plansCount > 0 && !$isCompactPlansView)
            @include('favorites.want-compare', ['id' => 'favorites-login-plan-button'])
        @endif
    @endguest
    @if ($plansCount == 0)
        <div class="favorites title">У вас пока нет избранных квартир</div>
    @endif
</div>
