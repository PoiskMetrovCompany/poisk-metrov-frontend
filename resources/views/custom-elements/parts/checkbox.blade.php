@php
    $checkedClass = '';

    if (!isset($subclass)) {
        $subclass = '';
    }

    if (isset($checked) && $checked == true) {
        $checked = true;
        $checkedClass = 'checked';
    } else {
        $checked = false;
    }

    $reallyAllowMultiple =
        !isset($options) ||
        (isset($options->allowMultiple) && $options->allowMultiple == true) ||
        (isset($allowMultiple) && $allowMultiple == true);

    if (!$reallyAllowMultiple && $subclass == '') {
        $subclass = 'radio-button';
    }
@endphp

<div class="pseudo-checkbox {{ $subclass }} {{ $checkedClass }}">
    <div class="icon checkbox-check"></div>
</div>
