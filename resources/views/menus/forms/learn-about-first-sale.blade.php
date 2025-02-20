<div id="learn-about-first-sale-menu" class="sub-menus background">
    <form autocomplete="off" id="learn-about-first-sale" class="sub-menus card">
        @csrf
        <div class="sub-menus form">
            <div class="sub-menus top">
                <div class="sub-menus header">
                    <div class="sub-menus title">
                        Узнать о старте продаж
                    </div>
                    <div class="sub-menus subtitle">
                        <div><span class="red-highlight">*</span> -  обязательное для заполнения поле</div>
                    </div>
                </div>
                <div class="sub-menus close">
                    <div class="icon action-close d16x16 orange"></div>
                </div>
            </div>
            <div class="sub-menus inputs">
                @include('inputs.name', ['required' => "*"])
                @include('inputs.phone', ['required' => "*"])
            </div>
            <div class="get-free-catalogue where-send-grid">
                <div class="get-free-catalogue where-send-title">Выберите удобный способ связи</div>
                <div id="learn-about-first-sale-buttons" class="document-download grey-container menu">
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
                    <div class="tab disabled" data-name="Скачать на сайте">
                        <div class="document-download with-icon">
                            Звонок
                            <div class="icon phone d16x16 disabled"></div>
                        </div>
                    </div>
                </div>
            </div>
            @include('common.personal-info-agreement', ['buttonText' => 'Оставить&nbspзаявку', 'type' => 'modal'])
        </div>
    </form>
</div>
