@if (count($similar) > 0)
    <div class="catalog home">
        <div class="section-header">
            <div class="title first">Похожие планировки</div>
            <div id="plans-grid-buttons" class="arrow-buttons-container">
                @include('buttons.arrow-left')
                @include('buttons.arrow-right')
            </div>
        </div>
        <div id="plans-grid" class="plans-filter apartment-dropdown card-grid horizontal">
            @foreach ($similar as $similarItem)
                @include('cards.plan.card', [
                    'name' => '',
                    'offerId' => $similarItem['offer_id'],
                    'planUrl' => $similarItem['plan_URL'],
                    'formattedPrice' => $similarItem['displayPrice'],
                    'type' => $similarItem['apartment_type'],
                    'area' => $similarItem['area'],
                    'floor' => $similarItem['floor'],
                    'maxFloor' => $similarItem['floors_total'],
                    'quarter' => $similarItem['ready_quarter'],
                    'builtYear' => $similarItem['built_year'],
                    'material' => $similarItem['building_materials'],
                    'finishing' => $similarItem['renovation'],
                    'isFavoriteApartment' => $similarItem['isFavoriteApartment'],
                    'priceDifference' => $similarItem['priceDifference'],
                ])
                @include('chart.price-chart', [
                    'historicApartmentPrice' => $similarItem['displayPrice'],
                    'code' => $similarItem['offer_id'],
                    'apartment_type' => $similarItem['apartment_type'],
                    'area' => $similarItem['area'],
                    'history' => $similarItem['history'],
                    'firstDate' => $similarItem['firstDate'],
                    'lastDate' => $similarItem['lastDate'],
                    'lastChanges' => $similarItem['lastChanges'],
                ])
            @endforeach
        </div>
    </div>
@endif
