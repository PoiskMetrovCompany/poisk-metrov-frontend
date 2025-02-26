@if (isset($buildingProcess) && count($buildingProcess) > 0)
    @vite('resources/js/gallery/buildingProgress.js')
    <div class="construction-progress base-container">
        <div class="construction-progress header">
            <h2 class="real-estate title">Ход строительства</h2>
            <div id="building-progress-gallery-buttons" class="arrow-buttons-container">
                @include('buttons.arrow-left')
                @include('buttons.arrow-right')
            </div>
        </div>
        <div id="building-progress-gallery" class="construction-progress container">
            @foreach ($buildingProcess as $item)
                <div class="construction-progress item">
                    <img src="{!! $item->image_url !!}">
                    <div>{{ $item->date }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endisset
