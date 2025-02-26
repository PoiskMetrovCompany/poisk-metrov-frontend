@php
    if (!isset($required)) {
        $required = false;
    }

    if (!isset($type)) {
        $type = 'text';
    }
@endphp

<text-input @isset($id) id="{{ $id }}" @endisset>
    <fieldset>
        @include('custom-elements.parts.input.legend')
        <div class="input-container">
            <input type="{{ $type }}" placeholder="{!! $placeholder ?? '' !!}"
                @if ($required) required @endif @isset($textOnly) textonly @endisset
                @isset($value) value="{{ $value }}" @endisset>
            @include('custom-elements.parts.input.side-icons')
        </div>
    </fieldset>
    @include('custom-elements.parts.input.error')
</text-input>
