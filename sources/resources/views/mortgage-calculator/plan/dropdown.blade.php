<div class="mortgage-programs dropdown-card base-container" offersCount="{{ $offersCount }}">
    <div class="mortgage-programs dropdown-card primary-card">
        @include('mortgage-calculator.plan.bubbles')
        @include('common.divider')
        @include('mortgage-calculator.plan.stats')
    </div>
    @foreach ($mortgages as $offer)
        @include('mortgage-calculator.plan.offer')
    @endforeach
</div>
