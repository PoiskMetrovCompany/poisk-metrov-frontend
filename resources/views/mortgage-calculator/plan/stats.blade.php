<div class="mortgage-programs dropdown-card stats">
    <div class="mortgage-programs dropdown-card percentage">
        от {{ $minPercentage }} %
    </div>
    <div class="mortgage-programs dropdown-card text">
        от {{ $priceFormattingService->fullPrice($minMonthlySumm) }}/мес.
    </div>
    <div class="mortgage-programs dropdown-card text">
        {{ $minYear }}-{{ $maxYear }} лет
    </div>
    <div class="mortgage-programs dropdown-card text">
        {{ $priceFormattingService->priceToText($minAmount, '', '', 1000000) }}-{{ $priceFormattingService->priceToText($maxAmount, '', ' млн ₽', 1000000) }}
    </div>
    <div></div>
    @include('mortgage-calculator.plan.unfold-button')
</div>
