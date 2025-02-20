@php
    $chatEnabled = boolval(config('app.chat_enabled'));
@endphp

@if($chatEnabled)
    @vite('resources/js/pusher.js')
@endif