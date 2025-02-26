<div class="client-register header">
    <h1>
        Новый клиент
        <button type="button">
            @include('icons.close')
        </button>
    </h1>
    <div class="client-register dropdown-with-title">
        <h6>Статус</h6>
        @include('custom-elements.select', [
            'id' => 'client-status-dropdown',
            'placeholder' => 'Новый',
            'allowMultiple' => false,
            'options' => [
                'Новый',
                'Встреча',
                'Фиксация',
                'Показ',
                'Бронь',
                'Ипотека',
                'Договор',
                'Отказался',
                'Архив',
                'Отложенный спрос',
                'Проблема с ипотекой',
            ],
        ])
    </div>
</div>
