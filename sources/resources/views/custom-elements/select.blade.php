@php
    if (!isset($allowMultiple)) {
        $allowMultiple = false;
    }

    if (isset($allData)) {
        if (!isset($options)) {
            $options = $allData->data;
        }

        $allowMultiple = $allData->allowMultiple;
    }
@endphp

<custom-select @isset($id)
 id="{{ $id }}"
 @endisset tabindex="-1"
    allowMultiple="{{ $allowMultiple }}">
    @isset($customIcon)
        @include($customIcon)
    @endisset
    @isset($legend)
        <legend>{{ $legend }}</legend>
    @endisset
    @include('custom-elements.parts.placeholder')
    @include('custom-elements.parts.counter')
    @include('icons.arrow-tailless')
    @include('custom-elements.parts.option-list')
</custom-select>
