<button type="button" @isset($dropdown)
@include('dropdown.copy-option-attributes', ['option' => $dropdown->{$dropdownKey}])
@endisset>
    {{ $text }}
    <div>
        @isset($count)
            {{ $count }}
        @endisset
    </div>
</button>
