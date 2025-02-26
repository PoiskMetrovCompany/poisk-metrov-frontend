@php
    if (isset($defaultOption) && $defaultOption == $option) {
        $isSelected = true;
    } else {
        $isSelected = false;
    }
@endphp

<li is="custom-option" @if ($isSelected) selected="true" @endif>
    @include('custom-elements.parts.checkbox', ['checked' => $isSelected])
    <span>
        @if (is_string($option))
            {{ $option }}
        @elseif(is_object($option))
            {{ $option->displayName }}
        @endif
    </span>
</li>
