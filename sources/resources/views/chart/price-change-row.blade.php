<div class="price-change row">
    <div>{{ $date }}</div>
    <div class="price-change price-difference">
        @if (str_contains($change, '-'))
            <div class="icon content-triangle green rotated d20x20"></div>
        @elseif (str_contains($change, '+'))
            <div class="icon content-triangle red d20x20"></div>
        @else
            <div class="icon price-point orange d8x8"></div>
        @endif
        {{ $change }}
    </div>
    <div>{{ $price }}</div>
</div>
