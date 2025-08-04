<div class="complex-top-container">
    @isset($preview)
        <img src="data:image/png;base64,{{ $preview }}" class="complex-preview">
    @endisset
    <div class="object-description">
        <div class="complex-page-price">
            <div class="description-title">Минимальная цена</div>
            <div>от {{ $complex['minPriceDisplay'] }}</div>
        </div>
        <div class="object-description-container">
            @include('export-pdf.real-estate.parts.preview-description-line', [
                'title' => 'Срок сдачи',
                'value' => $complex['builtYear'],
            ])
            @include('export-pdf.real-estate.parts.preview-description-line', [
                'title' => 'Отделка',
                'value' => $complex['renovation'],
            ])
            @include('export-pdf.real-estate.parts.preview-description-line', [
                'title' => 'Тип дома',
                'value' => $complex['buildingMaterials'],
            ])
            @include('export-pdf.real-estate.parts.preview-description-line', [
                'title' => 'Этажность',
                'value' => $complex['floorsTotal'],
            ])
        </div>
    </div>
</div>
