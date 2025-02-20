<div class="page">
    @include('export-pdf.shared.parts.header')
    @include('export-pdf.shared.parts.divider')
    <div class="content-container">
        <div class="complexes-title-container">
            <div class="complex-title">{{ $complex['name'] }}</div>
            <div class="complexes-description">
                <p>{{ $complex['description'] }}</p>
                @include('export-pdf.shared.parts.location-plaque')
            </div>
        </div>
        @include('export-pdf.shared.parts.gallery')
    </div>
</div>
