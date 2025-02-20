<form id="call-from-chat" class="chat-call base-container" autocomplete="off">
    @include('inputs.name')
    @include('inputs.phone')
    @include('common.personal-info-agreement')
    @include('chat.button.close')
</form>
@include('buttons.common', [
    'buttonText' => 'Перезвоните мне',
    'buttonId' => 'chat-quick-call-button',
])
