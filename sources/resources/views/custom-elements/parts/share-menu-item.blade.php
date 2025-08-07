<button>
    @isset($icon)
        @include('icons.icon', ['iconClass' => $icon, 'iconColor' => 'orange'])
    @endisset
    <div type="text">{{ $text }}</div>
</button>
