<div class="price-change container" id="to-price-chart-{{ $idModifier }}">
    <input type='hidden' value="{{ $idModifier }}" id="chartId">
    <div class="price-change left-item">
        @if (str_contains($priceDifference, '+'))
            <div class="icon content-price-dynamic d20x20 red"></div> {{ $priceDifference }}
            @if (!isset($hidePriceChangeButtonText) || $hidePriceChangeButtonText == false)
                изменение цены
            @endif
        @elseif (str_contains($priceDifference, '-'))
            <div class="icon content-price-dynamic d20x20 green rotated"></div> {{ $priceDifference }}
            @if (!isset($hidePriceChangeButtonText) || $hidePriceChangeButtonText == false)
                изменение цены
            @endif
        @else
            <div class="icon price-point d8x8 orange"></div> Цена не менялась
        @endif
    </div>
    <div class="price-change to-chart-icon">
        <div class="icon arrow-chevron-right orange d16x16"></div>
    </div>
</div>
