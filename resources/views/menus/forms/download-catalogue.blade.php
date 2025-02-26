<div class="sub-menus background" id="get-free-catalogue-popup">
    <form autocomplete="off" id="get-free-catalogue-form" class="sub-menus card">
        <div class="sub-menus form">
            @csrf
            <div class="sub-menus top-half">
                <div class="sub-menus top">
                    <div class="sub-menus header">
                        <div class="sub-menus title">
                            @if ($selectedCity == 'novosibirsk')
                                Скачать каталог новостроек Новосибирска
                            @endif
                            @if ($selectedCity == 'st-petersburg')
                                Скачать каталог новостроек Санкт-Петербурга
                            @endif
                        </div>
                    </div>
                    <div class="sub-menus close">
                        <div class="icon action-close d16x16 orange"></div>
                    </div>
                </div>
                <div class="sub-menus description">
                    Оставьте номер телефона и мы отправим каталог вам на мессенджер
                </div>
            </div>
            @include('inputs.phone')
            <div class="get-free-catalogue where-send-grid">
                <div class="get-free-catalogue where-send-title">Куда отправить?</div>
                <div id="get-catalogue-buttons" class="document-download grey-container menu">
                    <div class="tab enabled" data-name="WhatsApp">
                        <div class="document-download with-icon">
                            WhatsApp
                            <div class="icon whatsapp d16x16 disabled"></div>
                        </div>
                    </div>
                    <div class="tab disabled" data-name="Telegram">
                        <div class="document-download with-icon">
                            Telegram
                            <div class="icon telegram d16x16 disabled"></div>
                        </div>
                    </div>
                    <div class="tab disabled" data-name="Скачать на сайте" style="display: none">
                        <div class="document-download with-icon">
                            Скачать на сайте
                            <div class="icon phone d16x16 disabled" style="display: none"></div>
                        </div>
                    </div>
                </div>
            </div>
            @include('common.personal-info-agreement', [
                'buttonText' => 'Получить&nbsp;каталог',
                'type' => 'modal',
            ])
        </div>
    </form>
</div>
