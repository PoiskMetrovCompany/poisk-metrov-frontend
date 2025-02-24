<div id="reserve-apartment" class="sub-menus background">
    <form autocomplete="off" id="reserve-apartment-form" class="sub-menus card">
        @csrf
        <div class="sub-menus form">
            <div class="sub-menus top-half">
                <div class="sub-menus top">
                    <div class="sub-menus header">
                        <div class="sub-menus title" style="width: 90%;">
                            Забронируйте квартиру прямо сейчас!
                        </div>

                    </div>
                    <div class="sub-menus close">
                        <div class="icon action-close d16x16 orange"></div>
                    </div>
                </div>
                <div class="sub-menus description">
                    Наш менеджер по бронированию свяжется с вами в ближайшее время для подтверждения брони
                </div>
            </div>
            <div class="sub-menus inputs">
                @include('inputs.name', ['required' => "*"])
                @include('inputs.last-name', ['required' => "*"])
                @include('inputs.middle-name', ['required' => "*"])
                @include('inputs.phone', ['required' => "*"])
            </div>
            <div class="get-free-catalogue where-send-grid">
                <div class="get-free-catalogue where-send-title">Выберите удобный способ связи</div>
                <div id="reserve-apartment-buttons" class="document-download grey-container menu">
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
            @include('common.personal-info-agreement', ['type' => 'modal'])
        </div>
    </form>
</div>
