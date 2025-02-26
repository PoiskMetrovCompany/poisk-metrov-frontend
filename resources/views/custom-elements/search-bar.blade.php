<search-bar>
    @include('icons.search')
    <input type="text" @isset($placeholder) placeholder="{{ $placeholder }}" @endisset>
    @isset($dropdownTemplate)
        @include($dropdownTemplate)
    @endisset
</search-bar>
