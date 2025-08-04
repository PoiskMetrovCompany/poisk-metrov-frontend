<div id="{{ $id }}" class="filter base-container">
    <span>{{ $title }}</span>
    @include('dropdown.elements.counter')
    @include('icons.filter-arrow')
    @include($optionsTemplate)
</div>
