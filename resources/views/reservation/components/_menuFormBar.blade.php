<div class="actions-navbar">
    <div class="actions-navbar__container actions-navbar__min">
        @foreach($menuLists as $item => $key)
            <div class="actions-navbar__item actions-navbar__point @if ($key['name'] === $chatName) actions-navbar__chat-active @endif"
                data-section="{{ $key['data-section'] }}">
                {{ $key['name'] }}
            </div>
        @endforeach
    </div>
</div>
