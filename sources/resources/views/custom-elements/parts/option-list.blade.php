<ul is="option-list">
    @foreach ($options as $option)
        @include('common.divider')
        @include('custom-elements.parts.option')
    @endforeach
</ul>
