<form class="chat-window form invalid">
    <div>
        <button type="button">
            @include('icons.chat.attachment')
        </button>
        <button type="button">
            @include('icons.chat.more')
        </button>
    </div>
    <textarea type="text" rows="1" name="message" required placeholder="Сообщение"></textarea>
    <button type="submit" class="common-button">
        @include('icons.arrow-right')
    </button>
</form>
