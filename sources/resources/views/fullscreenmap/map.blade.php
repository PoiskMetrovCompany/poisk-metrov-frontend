<div id="{{ $id ?? 'full-screen-map' }}" class="full-screen-map map">
    <div class="full-screen-map top-buttons">
        @if (isset($showCatalogueButton) && $showCatalogueButton == 'true')
            <a href="/catalogue" class="full-screen-map button-with-icon">
                <div class="icon burger"></div>
                <span>Показать объекты списком</span>
            </a>
        @endif
        <div class="full-screen-map close">
            <div class="icon action-close"></div>
        </div>
    </div>
</div>
