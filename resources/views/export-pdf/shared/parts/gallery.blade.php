@php
    if (count($complex['gallery']) >= 3) {
        $secondImage = $fileService->getFileAsBase64($complex['gallery'][1]);
        $thirdImage = $fileService->getFileAsBase64($complex['gallery'][2]);
    }
@endphp

<div class="images-grid">
    @isset($preview)
        <img src="data:image/png;base64,{{ $preview }}">
    @endisset
    @if (count($complex['gallery']) >= 3)
        @isset($secondImage)
            <img src="data:image/png;base64,{{ $secondImage }}">
        @endisset
        @isset($thirdImage)
            <img src="data:image/png;base64,{{ $thirdImage }}">
        @endisset
    @endif
</div>
