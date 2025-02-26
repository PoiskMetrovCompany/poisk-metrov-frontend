<div class="sub-menus background" id="get-free-catalogue-popup">
    <form autocomplete="off" id="get-free-catalogue-form" class="sub-menus card">
        @csrf
        <div class="get-free-catalogue header">
            <div class="get-free-catalogue title">
                Получите бесплатный каталог новостроек вашего города!
            </div>
            <div class="get-free-catalogue description">
                Оставьте номер телефона и мы отправим каталог вам на мессенджер
            </div>
        </div>
        <div class="get-free-catalogue form">
            @include('inputs.phone')
        </div>
        <div class="get-free-catalogue where-send-grid">
            <div class="get-free-catalogue where-send-title">Куда отправить?</div>
            <div id="get-catalogue-buttons" class="document-download grey-container force-grey">
                <div class="tab disabled" data-name="WhatsApp">
                    <div class="document-download with-icon">
                        WhatsApp
                        <div class="icon whatsapp d16x16 disabled"> </div>
                    </div>
                </div>
                <div class="tab disabled" data-name="Telegram">
                    <div class="document-download with-icon">
                        Telegram
                        <div class="icon telegram d16x16 disabled"> </div>
                    </div>
                </div>
                <div class="tab disabled" data-name="Скачать на сайте" style="display: none">
                    <div class="document-download with-icon">
                        Скачать на сайте
                        <div class="icon phone d16x16 disabled" style="display: none"> </div>
                    </div>
                </div>
            </div>
        </div>
        @include('common.personal-info-agreement', ['buttonText' => 'Получить&nbsp;каталог', 'type' => 'modal'])
    </form>
</div>
