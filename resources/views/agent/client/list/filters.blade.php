<div class="agent filters">
    @include('custom-elements.search-bar', [
        'placeholder' => 'Поиск по агенту, клиенту...',
    ])
    @include('custom-elements.select', [
        'id' => 'client-list-date',
        'placeholder' => 'Дата добавления',
        'options' => ['1', '2', '3'],
    ])
    @include('custom-elements.select', [
        'id' => 'client-list-status',
        'placeholder' => 'Статус',
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
