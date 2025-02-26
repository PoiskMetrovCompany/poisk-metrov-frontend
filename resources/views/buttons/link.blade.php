<a @isset($buttonId) id="{{ $buttonId }}" @endisset @if(isset($subclass)) class="common-button {{ $subclass }}" @else
    class="common-button" @endif href="{{ $link }}">
    {{ $buttonText }}
</a>