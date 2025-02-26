<div id="{{ $id }}" tabindex="-1" class="plan-card card-button {{ $subclass ?? '' }}">
    <div class="icon action-share-grey d24x24 black"></div>
    @include('common.hint', ['text' => 'Поделиться'])
    <div class="building-cards social-media menu with-border">
        <div class="building-cards social-media text-with-icon">
            <div class="icon link-transparent orange"></div>
            <div>Скопировать ссылку</div>
        </div>
        <div class="building-cards social-media text-with-icon" id="{{ $id }}-share-telegram">
            <div class="icon telegram-transparent orange d24x24"></div>
            <div>Telegram</div>
        </div>
        <div class="building-cards social-media text-with-icon" id="{{ $id }}-share-watsapp">
            <div class="icon whatsapp-transparent orange d24x24"></div>
            <div>WhatsApp</div>
        </div>
        <div class="building-cards social-media text-with-icon">
            <div>Закрыть</div>
        </div>
    </div>
</div>
