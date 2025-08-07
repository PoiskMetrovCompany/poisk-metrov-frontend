<a href="{{ $link ?? 'javascript:void(0)' }}" selected="{{ var_export($isCurrentPage) }}"
    @isset($id) id="{{ $id }}" @endisset>
    @include('icons.icon', ['iconClass' => $icon, 'iconColor' => 'grey5'])
    <div>{{ $title }}</div>
</a>
