<div id="{{ $id }}" class="filter-toggles container" allowMultiple={{ $elements->allowMultiple }}>
    <span>{{ $buttonsTitle }}</span>
    <div
        @isset($containerClass)
            class="{{ $containerClass }}"
        @else
            class="filter-toggles buttons-container fit-any"
        @endisset>
        @foreach ($elements->data as $option)
            <button type="button" class="filter-toggles button room" @include('dropdown.copy-option-attributes', ['option' => $option])>
                @isset($option->shortName)
                    {{ $option->shortName }}
                @else
                    {{ $option->displayName }}
                @endisset
            </button>
        @endforeach
    </div>
</div>
