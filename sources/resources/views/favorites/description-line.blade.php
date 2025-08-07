@if (isset($descriptionLineValue) && $descriptionLineValue != '')
    <div class="expanded-plan-card type-title">
        {{ $descriptionLineName }}
        <div> </div>
    </div>
    <div class="expanded-plan-card description">{{ $descriptionLineValue }}</div>
@endif
