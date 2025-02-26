<div class="page">
    @include('export-pdf.shared.parts.header')
    @include('export-pdf.shared.parts.divider')
    <div class="complex-container">
        @include('export-pdf.real-estate.parts.preview')
        @include('export-pdf.shared.parts.divider')
        @include('export-pdf.shared.parts.about')
        @include('export-pdf.shared.parts.divider')
        @include('export-pdf.real-estate.parts.stats')
    </div>
</div>
