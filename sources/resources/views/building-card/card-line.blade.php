@isset($data[$num])
    <div class="building-cards description-line">
        <div class="building-cards title-section">{{ $data[$num]['name'] }}</div>
        <div class="building-cards description">от {{ $data[$num]['min-square'] }} м²</div>
        <div class="building-cards description">от {{ $data[$num]['min-price'] }}</div>
    </div>
@else
    <div class="building-cards description-line">
        <div class="building-cards title-section">&nbsp;</div>
        <div class="building-cards description">&nbsp;</div>
        <div class="building-cards description">&nbsp;</div>
    </div>
@endisset
