@php
    $isRequired = false;

    if (isset($required)) {
        $isRequired = $required;
    }
@endphp

<fieldset id="{{ $id }}">
    @isset($header)
        <legend>
            {{ $header }}
            @if ($isRequired)
                <span>*</span>
            @endif
        </legend>
    @endisset
    @if (isset($isTextarea) && $isTextarea == true)
        <textarea rows="5" @if ($isRequired) required @endif
            @isset($placeholder)
            placeholder="{{ $placeholder }}"
        @endisset></textarea>
    @else
        <input @isset($type)
type="{{ $type }}"
@else
 type="text"
 @endisset
            @if ($isRequired) required @endif
            @isset($placeholder)
                placeholder="{{ $placeholder }}"
            @endisset />
    @endif
</fieldset>
