@if ($checkValue != '')
    <div class="plan description-item">
        {{ $itemTitle }}
        <div @isset($itemId)
            id="{{ $itemId }}"
        @endisset
            class="plan description-value">{{ $itemValue }}</div>
    </div>
@endif
