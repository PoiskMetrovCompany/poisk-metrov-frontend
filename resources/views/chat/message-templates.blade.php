<div id="chat-message-templates" style="display: none">
    @include('chat.messages.sender', [
        'messageText' => '',
        'time' => '',
    ])
    @include('chat.messages.reciever', [
        'messageText' => '',
        'recieverName' => '',
        'profilePicUrl' => '',
        'time' => '',
    ])
    @include('chat.messages.agent', [
        'time' => '',
        'messageText' => '',
    ])
    @include('chat.messages.date', ['date' => ''])
    @include('chat.messages.categories')
</div>
