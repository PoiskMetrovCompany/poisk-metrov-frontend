<div id="login-failure" class="auth-popup background">
    <div class="auth-popup base-container">
        @include('authorization.popup-header')
        <div class="login icon-with-text">
            <div class="login fail-icon">
                <div class="icon action-close white d20x20"></div>
            </div>
            <div>Ошибка</div>
        </div>
        <div id="auth-error-text" class="login text-container">
            Произошла ошибка, обновите страницу позднее
        </div>
    </div>
</div>
