<div class="custom-dropdown base-container" allowMultiple={{ $options->allowMultiple }}>
    @foreach ($options->data as $option)
        <div class="names-dropdown item" @include('dropdown.copy-option-attributes')>
            <div>
                @include('custom-elements.parts.checkbox')
                <span>{{ $option->displayName }}</span>
            </div>
        </div>
    @endforeach
</div>
