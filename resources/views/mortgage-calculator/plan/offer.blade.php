<div class="mortgage-programs offer-card base-container" mortgageId={{ $offer['mortgage_id'] }}
    minRate="{{ $offer['min_rate'] }}" maxRate="{{ $offer['max_rate'] }}" minInitialFee="{{ $offer['min_initial_fee'] }}"
    maxInitialFee="{{ $offer['max_initial_fee'] }}" minAmount="{{ $offer['from_amount'] }}"
    maxAmount="{{ $offer['to_amount'] }}" minYear="{{ $offer['from_year'] }}" maxYear="{{ $offer['to_year'] }}">
    <img src="storage/banks/png/{{ $offer['bank_icon'] }}.png">
    <div>
        <div class="mortgage-programs offer-card title">{{ $offer['product_name'] }} </div>
        <div class="mortgage-programs offer-card info-container">
            <div class="mortgage-programs dropdown-card text">
                от {{ $offer['min_rate'] }} %
            </div>
            <div class="mortgage-programs dropdown-card text">
                от {{ $priceFormattingService->fullPrice($offer['min_monthly_fee']) }}/мес.
            </div>
            <div class="mortgage-programs dropdown-card text">
                {{ $offer['from_year'] }}-{{ $offer['to_year'] }} лет
            </div>
            <div class="mortgage-programs dropdown-card text">
                {{ $priceFormattingService->priceToText($offer['from_amount'], '', '', 1000000) }}-{{ $priceFormattingService->priceToText($offer['to_amount'], '', ' млн ₽', 1000000) }}
            </div>
        </div>
    </div>
    <div class="mortgage-programs offer-card info-icon-container" style="display: none">
        @include('icons.info')
    </div>
</div>
