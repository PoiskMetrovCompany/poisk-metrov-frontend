@php
    $chatEnabled = boolval(config('app.chat_enabled'));
@endphp

@if ($chatEnabled)
    <div class="chat-window base-container">
        @vite('resources/js/chat/chatLoader.js')
        @include('chat.button.close')
        @include('chat.header')
        <div class="chat-window message-container">
            @include('chat.call-me-back')
            @include('chat.call-for-apartment')
        </div>
        @include('chat.message-templates')
        @include('chat.funnel')
        @include('chat.input')
    </div>
@endif