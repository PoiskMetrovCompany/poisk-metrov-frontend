<li @if (isset($clientOnly) && $clientOnly == true) clientonly="true" @endif>
    @include('icons.icon', ['iconClass' => $icon, 'iconColor' => 'black'])
    <a href="{{ $href }}">{{ $optionText }}</a>
</li>
