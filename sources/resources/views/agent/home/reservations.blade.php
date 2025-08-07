<apartment-reservations>
    @include('agent.home.reservations.card', [
        'icon' => 'reservation-request',
        'header' => 'Заявки на бронь',
        'line1' => 'Продление',
        'line2' => 'В обработке',
        'line1Count' => '1',
        'line2Count' => '7',
    ])
    @include('agent.home.reservations.card', [
        'icon' => 'reservation-relevant',
        'header' => 'Актуальные брони',
        'line1' => 'Платные',
        'line2' => 'Бесплатные',
        'line1Count' => '61',
        'line2Count' => '5',
    ])
    @include('agent.home.reservations.card', [
        'icon' => 'reservation-running-out',
        'header' => 'Брони заканчиваются',
        'line1' => 'Заканчиваются',
        'line2' => 'Закончились',
        'line1Count' => '1',
        'line2Count' => '8',
    ])
    @include('agent.home.reservations.card', [
        'icon' => 'reservation-file',
        'header' => 'Договоры',
        'line1' => 'Запись на договор',
        'line2' => 'Пред. договор записан',
        'line1Count' => '5',
        'line2Count' => '1',
    ])
</apartment-reservations>
