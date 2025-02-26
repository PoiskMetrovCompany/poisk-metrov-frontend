<div id="get-real-estate-presentation-menu" class="sub-menus background">
    <form autocomplete="off" id="get-real-estate-presentation" class="sub-menus card">
        @csrf
        <div class="sub-menus form">
            <div class="sub-menus top">
                <div class="sub-menus header">
                    <div class="sub-menus title">
                        Скачать презентацию
                    </div>
                </div>
                <div class="sub-menus close">
                    <div class="icon action-close d16x16 orange"></div>
                </div>
            </div>
            <div class="sub-menus inputs">
                @include('inputs.name')
                @include('inputs.phone')
            </div>
            <div class="get-free-catalogue where-send-grid">
                <div class="get-free-catalogue where-send-title">Куда отправить?</div>
                <div id="get-presentation-buttons" class="document-download grey-container">
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
                    <div id="download-presentation-button-on-form" class="tab disabled" data-name="Скачать на сайте">
                        <div class="document-download with-icon">
                            Скачать на сайте
                            <div class="icon phone d16x16 disabled" style="display: none"></div>
                        </div>
                    </div>
                </div>
            </div>
            @include('common.personal-info-agreement', ['buttonText' => 'Скачать', 'type' => 'modal'])
        </div>
    </form>
</div>
