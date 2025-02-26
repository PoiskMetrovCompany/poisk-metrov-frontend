@if (count($recommendations))
    <div class="base-container">
        @vite('resources/js/homePage/apartmentSuggestions.js')
        <div class="title-flex">
            <div class="title">
                Подборки квартир
            </div>
            @include('common.arrow-buttons-container', ['id' => 'apartment-suggestions-buttons'])
        </div>
        <div id="apartment-suggestions-cards" class="apartment-suggestions base-container">
            @php
                $cardAdded = false;
                $i = 0;
            @endphp
            @for ($i = 0; $i < count($recommendations); $i++)
                @if ($i == 2 && !$cardAdded)
                    @include('home-page.category-suggestion-card', [
                        'text' => 'Квартиры</br>и апартаменты бизнес-класса',
                    ])
                    @php
                        $i--;
                        $cardAdded = true;
                    @endphp
                @else
                    @include('cards.plan.card', [
                        'name' => '',
                        'offerId' => $recommendations[$i]['offer_id'],
                        'planUrl' => $recommendations[$i]['plan_URL'],
                        'formattedPrice' => $recommendations[$i]['displayPrice'],
                        'type' => $recommendations[$i]['apartment_type'],
                        'area' => $recommendations[$i]['area'],
                        'floor' => $recommendations[$i]['floor'],
                        'maxFloor' => $recommendations[$i]['floors_total'],
                        'quarter' => $recommendations[$i]['ready_quarter'],
                        'builtYear' => $recommendations[$i]['built_year'],
                        'material' => $recommendations[$i]['building_materials'],
                        'finishing' => $recommendations[$i]['renovation'],
                        'isFavoriteApartment' => $recommendations[$i]['isFavoriteApartment'],
                        'priceDifference' => $recommendations[$i]['priceDifference'],
                        'hidePriceChangeButtonText' => true,
                    ])
                    @include('chart.price-chart', [
                        'historicApartmentPrice' => $recommendations[$i]['displayPrice'],
                        'code' => $recommendations[$i]['offer_id'],
                        'apartment_type' => $recommendations[$i]['apartment_type'],
                        'area' => $recommendations[$i]['area'],
                        'history' => $recommendations[$i]['history'],
                        'firstDate' => $recommendations[$i]['firstDate'],
                        'lastDate' => $recommendations[$i]['lastDate'],
                        'lastChanges' => $recommendations[$i]['lastChanges'],
                    ])
                @endif
            @endfor
        </div>
    </div>
@endif
