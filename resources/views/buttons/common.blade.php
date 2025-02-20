<button type="button" @isset($buttonId) id="{{ $buttonId }}" @endisset class="common-button {{ $subclass ?? '' }}">
    @isset($buttonIcon)
        <div class="icon d24x24 {{ $buttonIcon }} white"> </div>
    @endisset
    {{ $buttonText }}
</button>
