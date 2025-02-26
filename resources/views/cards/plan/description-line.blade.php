@isset($valueToCheck)
    @if ($valueToCheck != '' && $valueToCheck != 0 && $valueToCheck != null)
        <div class="plan-card description">{{ $text }}</div>
    @endif
@else
    <div class="plan-card description">{{ $text }}</div>
@endisset