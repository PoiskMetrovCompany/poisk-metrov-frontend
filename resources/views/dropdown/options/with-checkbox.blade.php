<div id="{{ $id }}" class="custom-dropdown base-container">
    @include('search.mobile-header', [
        'headerTitle' => $context,
        'backButtonId' => "$id-back",
        'resetButtonId' => "$id-reset",
    ])
    <div class="search-bar text-search small" tabindex="-1">
        @include('icons.search')
        <input placeholder="{{ $context }}">
        @include('dropdown.elements.counter')
    </div>
    <div class="search-grid small-menu-list" field={{ $searchData->field }}>
        @foreach ($searchData->values as $item)
            <div class="names-dropdown item" @include('dropdown.names.copy-name-attributes', [
                'item' => $item,
                'field' => $searchData->field,
            ])>
                <div>
                    @include('custom-elements.parts.checkbox')
                    <span>{{ $item->name }}</span>
                    <span>{{ $context }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
