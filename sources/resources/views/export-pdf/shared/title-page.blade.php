<div class="page">
    <div class="pdf-header">
        <div class="title-page-name-container">
            @include('export-pdf.shared.parts.title')
            <div class="title-page-subtitle">
                Бесплатный сервис
                <br>
                Бронирования новостроек
            </div>
        </div>
        <div class="title-page-contacts">
            8 (800) 444-40-45
            <div class="title-page-contacts-subtitle">
                Ежедневно с 9:00 до 21:00
                <br>
                <a href="https://poisk-metrov.ru">poisk-metrov.ru</a>
            </div>
        </div>
    </div>
    <div class="title-page-title-container">
        Индивидуальное
        <br>
        предложение
    </div>
    <div class="title-page-footer">
        <div class="title-page-city-container">
            <div>Новосибирск</div>
            @include('export-pdf.shared.parts.address', [
                'address' => 'ул. Дуси Ковальчук, 276, ',
                'offices' => ['корп. 13, этаж 2'],
            ])
            @include('export-pdf.shared.parts.address', [
                'address' => 'ул. Кошурникова 33,',
                'offices' => ['5 этаж, офис 1-2'],
            ])
        </div>
        <div class="title-page-city-container">
            <div>Санкт-Петербург</div>
            @include('export-pdf.shared.parts.address', [
                'address' => 'ул. Парфёновская, 12,',
                'offices' => ['этаж 5, офис 509,', 'этаж 6, офис 609'],
            ])
        </div>
    </div>
</div>
