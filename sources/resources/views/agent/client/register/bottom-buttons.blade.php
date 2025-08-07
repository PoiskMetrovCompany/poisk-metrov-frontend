<div class="agent bottom-buttons">
    @include('buttons.common', [
        'buttonId' => 'client-register-cancel',
        'buttonText' => 'Отменить',
        'buttonIcon' => 'action-close',
    ])
    @include('buttons.submit', [
        'buttonId' => 'client-register-save',
        'buttonText' => 'Сохранить',
    ])
</div>
