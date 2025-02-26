<div id="{{ $id }}" tabindex="-1" class="search-catalogue dropdown container"
    @isset($inline)
        style="{{ $inline }}"
    @endisset>
    @include('dropdown.elements.title')
    @include('dropdown.elements.preview')
    @include('dropdown.elements.counter')
    @include('icons.filter-arrow')
    @include($optionsTemplate)
</div>
