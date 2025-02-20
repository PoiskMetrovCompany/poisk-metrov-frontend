<div class="client-register base-container">
    @php
        $sortedNamesForCity = $residentialComplexRepository->getSortedNamesForCity($selectedCity);
        $query = Request::query('residential_complex');
        $buildingName = null;

        if ($query != null) {
            $building = \App\Models\ResidentialComplex::where('code', $query)->first();

            if ($building) {
                $buildingName = $building->name;
            }
        }
    @endphp
    @include('agent.dropdown-grey', [
        'header' => 'Жилой комплекс',
        'id' => 'residential-complex-dropdown',
        'placeholder' => 'Выбрать',
        'allowMultiple' => false,
        'items' => $sortedNamesForCity,
        'defaultOption' => $buildingName,
    ])
    @include('agent.dropdown-grey', [
        'header' => 'Заявка клиента',
        'id' => 'client-request-dropdown',
        'placeholder' => 'Заявка',
        'allowMultiple' => false,
        'items' => ['Фиксация', 'Заявка на показ'],
    ])
    <div dummy="true"></div>
    <div class="client-register show-request">
        @include('agent.calendar', [
            'id' => 'show-date-calendar',
        ])
        @include('agent.dropdown-grey', [
            'id' => 'client-time-dropdown',
            'placeholder' => 'Время показа',
            'items' => [
                '08:00',
                '09:00',
                '10:00',
                '11:00',
                '12:00',
                '13:00',
                '14:00',
                '15:00',
                '16:00',
                '17:00',
                '18:00',
                '19:00',
                '20:00',
            ],
        ])
        @include('agent.dropdown-grey', [
            'id' => 'client-show-type-dropdown',
            'placeholder' => 'Тип показа',
            'items' => ['Онлайн', 'Оффлайн'],
        ])
        <div class="client-register deal-comment">
            @include('agent.field', [
                'id' => 'client-deal-comment',
                'placeholder' => 'Комментарий к заявке',
                'isTextarea' => true,
            ])
        </div>
    </div>
    <div class="client-register show-fixation">
        @include('custom-elements.buttons-grid', [
            'id' => 'apartment-types-buttons',
            'allowMultiple' => true,
            'buttons' => ['Студия', '1', '2', '3', '4+'],
        ])
        <div class="client-register checkboxes">
            @include('custom-elements.contained-checkbox', [
                'placeholder' => 'Коммерция',
                'id' => 'commerce-checkbox',
            ])
            @include('custom-elements.contained-checkbox', [
                'placeholder' => 'Парковка',
                'id' => 'parking-checkbox',
            ])
            @include('custom-elements.contained-checkbox', [
                'placeholder' => 'Кладовые',
                'id' => 'storages-checkbox',
            ])
        </div>
        <div class="client-register deal-comment">
            @include('agent.field', [
                'id' => 'client-deal-comment',
                'placeholder' => 'Комментарий к заявке',
                'isTextarea' => true,
            ])
        </div>
    </div>
</div>
