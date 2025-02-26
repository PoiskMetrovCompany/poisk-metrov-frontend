<div class="custom-dropdown base-container" allowMultiple={{ $options->allowMultiple }}>
    @foreach ($options->data as $item)
        <div class="custom-dropdown text-item" @include('dropdown.copy-option-attributes', ['option' => $item])>
            @include('custom-elements.parts.checkbox')
            <span>{{ $item->displayName }}</span>
        </div>
    @endforeach
</div>
