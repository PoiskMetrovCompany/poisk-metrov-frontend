<div class="about-complex">
    <div class="about-complex-subtitle">Об объекте</div>
    <div class="complex-details">
        @include('export-pdf.real-estate.parts.detail', [
            'iconFile' => 'walls',
            'value' => $complex['buildingMaterials'],
            'description' => 'тип стен',
        ])
        @if ($complex['parking'] != '')
            @include('export-pdf.real-estate.parts.detail', [
                'iconFile' => 'parking',
                'value' => $complex['parking'],
                'description' => 'стоянка',
            ])
        @endif
        @include('export-pdf.real-estate.parts.detail', [
            'iconFile' => 'ceilings',
            'value' => $complex['ceilingHeight'] . ' м',
            'description' => 'высота потолков',
        ])
        @php
            $corpusDescription = 'корпусов';

            if ($complex['corpuses'] > 4) {
                $corpusDescription = 'корпусов';
            } elseif ($complex['corpuses'] > 1) {
                $corpusDescription = 'корпуса';
            } else {
                $corpusDescription = 'корпус';
            }
        @endphp
        @include('export-pdf.real-estate.parts.detail', [
            'iconFile' => 'corpuses',
            'value' => $complex['corpuses'],
            'description' => $corpusDescription,
        ])
        @include('export-pdf.real-estate.parts.detail', [
            'iconFile' => 'floors',
            'value' => $complex['floorsTotal'],
            'description' => 'этажей',
        ])
    </div>
</div>
