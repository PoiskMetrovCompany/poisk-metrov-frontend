@isset($panorama)
    @if ($panorama != '')
        <div class="panorama base-container">
            <h2 class="real-estate title">Панорама</h2>
            <embed src="{{ $panorama }}" class="panorama" />
        </div>
    @endif
@endisset
