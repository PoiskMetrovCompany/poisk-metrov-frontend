<div id="login-form-phone-popup" class="auth-popup background">
    <div class="auth-popup base-container">
        @include('authorization.popup-header')
        <form autocomplete="off" id="login-form-phone" class="auth-popup form">
            @csrf
            <div class="login text-container">
                <div>
                    Войдите в личный кабинет, чтобы получить доступ ко всем возможностям.
                </div>
                <div>
                    У нас нет паролей - вход осуществляется по номеру телефона, на который мы звоним.
                </div>
            </div>
            @include('inputs.phone')
            <input type="submit" class="peinag button" value="Получить код">
            <div class="peinag container">
                <div class="peinag checkbox-borders">
                    <input name="consent-checkbox" type="checkbox" class="peinag checkbox" required>
                </div>
                <div class="peinag description">
                    Нажимая на кнопку, вы даете согласие на обработку<a href="/policy">своих персональных данных</a>
                    и согласие на получение<a href="/ads-agreement">рекламных рассылок</a>.
                </div>
            </div>
        </form>
    </div>
</div>
