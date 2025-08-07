@if (isset($checkValue) && $checkValue != '')
    <div class="expanded-plan-card type-title">
        {{ $title }}
        <div> </div>
    </div>
    <div class="expanded-plan-card description">{{ $value }}</div>
@endif
