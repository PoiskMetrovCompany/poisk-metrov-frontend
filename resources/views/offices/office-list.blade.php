<div class="offices items-container" id="{{ $containerId }}"
    style="{{ $selectedCity != $forCity ? 'display: none' : '' }}">
    @include($officeListTemplate)
</div>
