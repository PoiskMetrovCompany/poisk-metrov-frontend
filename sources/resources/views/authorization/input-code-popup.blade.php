<div id="login-form-code-popup" class="auth-popup background">
    <div class="auth-popup base-container">
        @include('authorization.popup-header')
        <form autocomplete="off" id="login-form-code" class="auth-popup form" onsubmit="return false;">
            @csrf
            <div class="login text-container">
                <div>
                    Вам поступит звонок на номер <br />
                    <span id="sent-number-id">-
                        <span class="login underlined" style="display: none">
                            Изменить
                        </span>
                    </span><br />
                    Отвечать необязательно.
                </div>
            </div>
            @include('inputs.code', [
                'codeInputTitle' => 'Введите последние 4 цифры номера',
                'id' => 'authorize-code',
            ])
            <div class="auth-popup code-recieve">
                <div id="send-code-again-button" class="common-button">Получить код заново</div>
                <span>Перезвонить повторно через 00:<span id="code-timer">59</span></span>
            </div>
        </form>
    </div>
</div>
