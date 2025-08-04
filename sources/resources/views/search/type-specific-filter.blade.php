<div id="{{ $id }}" class="filter base-container type-specific">
    <div class="text-with-icon">
        <div class="icon {{ $icon }} d20x20 orange"> </div>
        <span>{{ $previewName }}</span>
        @include('dropdown.elements.counter')
    </div>
    @include('icons.filter-arrow')
    @include('dropdown.options.with-checkbox', [
        'searchData' => $searchDataPart,
        'id' => $dropdownId,
        'context' => $dropdownContext,
    ])
    <div class="filter apply">
        @include('buttons.common', ['buttonText' => 'Применить'])
    </div>
</div>
