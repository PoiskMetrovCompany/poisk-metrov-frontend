<div class="sub-menus background" id="signup-base">
    <form autocomplete="off" id="signup-for-buildng-form" class="sub-menus card">
        @csrf
        <div class="sub-menus form">
            <div class="sub-menus top-half">
                <div class="sub-menus top">
                    <div class="sub-menus header">
                        <div class="sub-menus title">
                            Записаться на просмотр
                        </div>
                    </div>
                    <div class="sub-menus close">
                        <div class="icon action-close d16x16 orange"></div>
                    </div>
                </div>
                <div class="sub-menus subtitle">
                    <div class="sub-menus description">
                        Укажите свои контакты и менеджер свяжется с вами, чтобы уточнить удобное время
                    </div>
                    <div>
                        <span class="red-highlight">*</span> - обязательное для заполнения поле
                    </div>
                </div>
            </div>
            <div class="sub-menus inputs">
                @include('inputs.name', ['required' => "*"])
                @include('inputs.last-name', ['required' => "*"])
                @include('inputs.middle-name', ['required' => "*"])
                @include('inputs.phone', ['required' => "*"])
            </div>
            <div class="signup-popup communication-container">
                <div class="signup-popup sub-title">Выберите удобный способ связи</div>
                <div id="signup-for-building-buttons" class="document-download grey-container menu">
                    <div class="tab disabled">
                        <div class="document-download with-icon">
                            Звонок
                            <div class="icon phone d16x16 disabled"></div>
                        </div>
                    </div>
                    <div class="tab disabled" data-name="WhatsApp">
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
                </div>
            </div>
            @include('common/personal-info-agreement', ['buttonText' => 'Оставить&nbsp;заявку', 'type' => 'modal'])
        </div>
    </form>
</div>
