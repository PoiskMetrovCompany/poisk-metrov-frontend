@include('mortgage-calculator.category-selection-buttons')
<div id="calculator" class="mortgage-calculator base-container">
    @php
        $mortgagesByRate = $bankService->getSortedMortgages(
            'min_rate',
            'asc',
            [],
            [],
            $bankService->getMinimumAllowedMortgageAmount(),
            10,
            $bankService->minInitialFee,
        );
        $maxMortgageParameters = $bankService->getMaxMortgageParameters();
        $count = 0;
        $max = $bankService->countPossibleMortgages();

        foreach ($mortgagesByRate as $mortgages) {
            $count += count($mortgages['mortgages']);
        }

    @endphp
    <script>
        let maxMortgageParameters = {!! json_encode($maxMortgageParameters) !!};
        let maxMortgages = {{ $max }};
    </script>
    <form autocomplete="off" class="mortgage-calculator selection container">
        @include('mortgage-calculator.filters')
        @include('mortgage-calculator.results-card')
    </form>
    <div class="mortgage-programs programs-with-filters">
        <div class="mortgage-programs programs-with-filters top">
            <div class="mortgage-programs results-counter">
                @include('icons.house')<span>{{ $count }} предложений</span>
            </div>
            @include('favorites.sorting', [
                'id' => 'mortgages-sorting-dropdown',
                'items' => [
                    'Ставка по возрастанию',
                    'Ставка по убыванию',
                    'Платеж по возрастанию',
                    'Платеж по убыванию',
                ],
            ])
        </div>
        <div class="mortgage-programs base-container">
            @foreach ($mortgagesByRate as $mortgages)
                @include('mortgage-calculator.plan.dropdown', [
                    'notes' => $mortgages['notes'],
                    'offersCount' => $mortgages['offersCount'],
                    'minPercentage' => $mortgages['rate'],
                    'minPercentage' => $mortgages['minPercentage'],
                    'minMonthlySumm' => $mortgages['minMonthlySumm'],
                    'minYear' => $mortgages['minYear'],
                    'maxYear' => $mortgages['maxYear'],
                    'minAmount' => $mortgages['minAmount'],
                    'maxAmount' => $mortgages['maxAmount'],
                    'mortgages' => $mortgages['mortgages'],
                ])
            @endforeach
        </div>
    </div>
</div>
