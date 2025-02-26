<div class="call-form base-container">
    <form autocomplete="off" id="call-me-back-form" class="call-form container">
        <div class="call-form title">
            Найдем квартиру.
            <br>
            Поможем с ипотекой. <span class="link-highlight">Бесплатно!</span>
        </div>
        <div class="call-form input-container">
            @include('inputs.phone', ['placeholder' => 'Ваш&nbsp;телефон'])
            <input type="submit" class="peinag button" value="Перезвоните мне">
            <div>
                <div class="peinag container">
                    <div class="peinag checkbox-borders">
                        <input id="conscent-checkbox" name="consent-checkbox" type="checkbox" class="peinag checkbox"
                            required>
                    </div>
                    <div class="peinag description">
                        Нажимая на кнопку, вы даете согласие на обработку<a href="/policy">своих персональных данных</a>
                        и согласие на получение<a href="/ads-agreement">рекламных рассылок</a>.
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="call-form sent" id="call-me-form-sent">
        <div class="call-form title">
            Найдем квартиру.
            <br>
            Поможем с ипотекой. <span class="link-highlight">Бесплатно!</span>
        </div>
        <div class="call-form success">
            Наш менеджер перезвонит вам в ближайшее время
        </div>
        <div class="call-form failure">
            <div class="call-form error">
                <div class="icon action-close d20x20 white"></div>
            </div>
            Произошла ошибка, повторите позже
        </div>
    </div>
</div>
