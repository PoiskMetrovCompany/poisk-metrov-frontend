<div id="mortgage-type-selection-buttons" class="mortage-tabs">
    @php
        $programTypes = $bankService->getUniqueMortgageProgramData(true);
        $programTypes = array_merge([['name' => 'Все программы']], $programTypes);
    @endphp
    @foreach ($programTypes as $item)
        @include('mortgage-calculator.tab', [
            'enabled' => $item['name'] == 'Все программы',
            'dataName' => $item['name'],
            'tabText' => $item['name'],
        ])
    @endforeach
</div>
