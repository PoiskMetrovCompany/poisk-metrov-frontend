<share-button tabindex="-1">
    @include('icons.icon', ['iconClass' => 'action-share-grey', 'iconColor' => 'grey5'])
    @include('common.hint', ['text' => 'Поделиться'])
    <div type="menu">
        @include('custom-elements.parts.share-menu-item', [
            'icon' => 'link-transparent',
            'text' => 'Скопировать ссылку',
        ])
        @include('custom-elements.parts.share-menu-item', [
            'icon' => 'telegram-transparent',
            'text' => 'Telegram',
        ])
        @include('custom-elements.parts.share-menu-item', [
            'icon' => 'whatsapp-transparent',
            'text' => 'WhatsApp',
        ])
        @include('custom-elements.parts.share-menu-item', [
            'text' => 'Закрыть',
        ])
    </div>
</share-button>
