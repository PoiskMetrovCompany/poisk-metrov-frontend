<div class="real-estate-compilation header">
    <header class="real-estate-compilation client-register header">
        <h4>
            @isset($user)
                {{ $user->name }} {{ $user->surname }}
            @else
                Посетитель
            @endisset
        </h4>
        <div class="client-register dropdown-with-title">
            <h6>Ответственный</h6>
            @include('custom-elements.select', [
                'id' => 'agent-selection-dropdown',
                'placeholder' => 'Выберите агента',
                'allowMultiple' => false,
                'options' => $managerService->getManagerNames(),
            ])
        </div>
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
    </header>
    @include('custom-elements.buttons-grid', [
        'id' => 'compilation-type-buttons',
        'allowDeselect' => false,
        'selectedButton' => 'Подбор',
        'buttons' => ['Подбор', 'Презентации', 'Бронирование', 'Ипотека', 'Чат'],
    ])
</div>
