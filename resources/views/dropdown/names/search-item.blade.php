<div class="names-dropdown item" @include('dropdown.names.copy-name-attributes', ['item' => $item])>
    <div>
        <div class="icon {{ $icon }}"> </div>
        <span>{{ $item->name }}</span>
        <span>{{ $context }}</span>
    </div>
</div>
