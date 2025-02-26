<div class="sub-menus background" id="make-meeting">
    <form autocomplete="off" id="make-meeting-form" class="sub-menus card">
        @csrf
        <div class="sub-menus form">
            <div class="sub-menus top-half">
                <div class="sub-menus top">
                    <div class="sub-menus header">
                        <div class="sub-menus title">
                            Записаться на встречу
                        </div>
                    </div>
                    <div class="sub-menus close">
                        <div class="icon action-close d16x16 orange"></div>
                    </div>
                </div>
                <div class="sub-menus subtitle">
                    <div class="sub-menus description">
                        Наш специалист свяжется с вами в ближайшее время для подтверждения даты и времени визита
                    </div>
                    <div>
                        <span class="red-highlight">*</span> - обязательное для заполнения поле
                    </div>
                </div>
            </div>
            <div class="sub-menus inputs">
                <input type="hidden" id="office-address">
                @include('inputs.name', ['required' => "*"])
                @include('inputs.phone', ['required' => "*"])
            </div>
            <div class="signup-popup communication-container">
                <div class="signup-popup sub-title">Выберите удобный способ связи</div>
                <div id="make-meeting-buttons" class="document-download grey-container menu">
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