<input @isset($buttonId) id="{{ $buttonId }}" @endisset type="submit"
    class="common-button {{ $subclass ?? '' }}" value="{{ $buttonText }}" />
