@if (isset($renovationImages) && count($renovationImages) > 0)
    @vite('resources/js/gallery/renovationGallery.js')
    <div class="finishing base-container">
        <div class="finishing header">
            <h2 class="real-estate title">Отделка</h2>
        </div>
        <div class="finishing container-with-buttons">
            <div id="renovation-gallery" class="finishing container">
                @foreach ($renovationImages as $item)
                    <img src="{!! $item->renovation_url !!}">
                @endforeach
            </div>
            <div id="renovation-gallery-buttons" class="arrow-buttons-container">
                @include('buttons.arrow-left')
                @include('buttons.arrow-right')
            </div>
        </div>
    </div>
@endisset
