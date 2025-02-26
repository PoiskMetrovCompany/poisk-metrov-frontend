@php
    $chatEnabled = boolval(config('app.chat_enabled'));
@endphp

<div class="window-bottom-buttons-container">
    @if ($chatEnabled)
        @include('chat.open-button')
    @endif
    @include('common.up-button')
</div>
