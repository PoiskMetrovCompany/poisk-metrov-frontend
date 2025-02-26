@php
    $pageCount = ceil(count($apartments[$i]) / 6) + 1;
@endphp
@for ($j = 0; $j < $pageCount; $j++)
    <div class="plans-filter apartment-dropdown card-grid" page={{ $j + 1 }}
        @if ($j != 0) style="display: none" @endif>
        @for ($k = $j * 6; $k < $j * 6 + 6 && $k < count($apartments[$i]); $k++)
            @include('cards.plan.card', [
                'name' => $name,
                'offerId' => $apartments[$i][$k]['offer_id'],
                'planUrl' => $apartments[$i][$k]['plan_URL'],
                'formattedPrice' => $apartments[$i][$k]['displayPrice'],
                'type' => $apartments[$i][$k]['apartment_type'],
                'area' => $apartments[$i][$k]['area'],
                'floor' => $apartments[$i][$k]['floor'],
                'maxFloor' => $apartments[$i][$k]['floors_total'],
                'quarter' => $apartments[$i][$k]['ready_quarter'],
                'builtYear' => $apartments[$i][$k]['built_year'],
                'material' => $apartments[$i][$k]['building_materials'],
                'finishing' => $apartments[$i][$k]['renovation'],
                'isFavoriteApartment' => $apartments[$i][$k]['isFavoriteApartment'],
                'priceDifference' => $apartments[$i][$k]['priceDifference'],
            ])
            @include('chart.price-chart', [
                'historicApartmentPrice' => $apartments[$i][$k]['displayPrice'],
                'code' => $apartments[$i][$k]['offer_id'],
                'apartment_type' => $apartments[$i][$k]['apartment_type'],
                'area' => $apartments[$i][$k]['area'],
                'history' => $apartments[$i][$k]['history'],
                'firstDate' => $apartments[$i][$k]['firstDate'],
                'lastDate' => $apartments[$i][$k]['lastDate'],
                'lastChanges' => $apartments[$i][$k]['lastChanges'],
            ])
        @endfor
    </div>
@endfor
@include('common.page-buttons', [
    'id' => 'apartment-switch-buttons-' . $i,
    'pageCount' => $pageCount,
])
