@php
    $historyLength = count($history);
    $historyString = json_encode($history);
@endphp

<div class="sub-menus background" id="price-chart-modal-{{ $code }}">
    <input type="hidden" value="{{ $historyString }}" id="history-data-{{ $code }}">
    <div class="sub-menus card fit-content" id="price-chart-card">
        <div class="sub-menus chart-card">
            <div class="sub-menus top">
                <div class="sub-menus header">
                    <div class="sub-menus title">
                        @isset($apartment_type)
                            {{ $apartment_type }},
                        @endisset
                        {{ $area }} м² - {{ $historicApartmentPrice }}
                    </div>
                </div>
                <div class="sub-menus close">
                    <div class="icon action-close d16x16 orange"></div>
                </div>
            </div>
            <div>
                @if (count($history) > 0)
                    @include('chart.price-change-row', [
                        'date' => $history[0]['date'],
                        'change' => 'Начальная цена',
                        'price' => $priceFormattingService->fullPrice($history[0]['price']),
                    ])
                @endif
                @if (count($lastChanges) > 2)
                    @for ($i = 0; $i < 3; $i++)
                        @include('chart.price-change-row', [
                            'date' => $history[$historyLength - 3 + $i]['date'],
                            'change' => $lastChanges[$i]['change'],
                            'price' => $lastChanges[$i]['price'],
                        ])
                        <div class="price-change divider"></div>
                    @endfor
                @elseif(count($lastChanges) == 2)
                    @for ($i = 0; $i < count($lastChanges); $i++)
                        @include('chart.price-change-row', [
                            'date' => $history[$historyLength - 2 + $i]['date'],
                            'change' => $lastChanges[$i]['change'],
                            'price' => $lastChanges[$i]['price'],
                        ])
                        <div class="price-change divider"></div>
                    @endfor
                @elseif(count($lastChanges) > 0)
                    @include('chart.price-change-row', [
                        'date' => $history[$historyLength - 1]['date'],
                        'change' => $lastChanges[0]['change'],
                        'price' => $lastChanges[0]['price'],
                    ])
                    <div class="price-change divider"></div>
                @endif
            </div>
            <div class="price-change chart">
                <canvas id="price-chart-{{ $code }}"></canvas>
            </div>
            <div class="price-change date-marks">
                <div class="price-change date-mark">
                    {{ $firstDate }}
                </div>
                <div class="price-change date-mark">
                    {{ $lastDate }}
                </div>
            </div>
        </div>
    </div>
</div>
