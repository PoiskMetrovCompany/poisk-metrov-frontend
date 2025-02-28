@php
    $bookings = [
        [
            'title' => 'Проект',
            'description' => 'ЖК Пшеница',
        ],
        [
            'title' => 'Девелопер',
            'description' => 'Брусника',
        ],
        [
            'title' => 'Срок сдачи',
            'description' => '4 кв. 2025',
        ],
        [
            'title' => 'Корпус',
            'description' => 'Корпус 25',
        ],
        [
            'title' => 'Отделка',
            'description' => 'Черновая отделка',
        ],
        [
            'title' => 'Этаж',
            'description' => '9 из 17',
        ],
        [
            'title' => 'Номер квартиры',
            'description' => '578',
        ],
        [
            'title' => 'Общая площадь',
            'description' => '30.2 м²',
        ],
        [
            'title' => 'Жилая площадь',
            'description' => '15.2 м²',
        ],
    ];

    $accordions = [
        [
            'title' => 'Личные данные',
            'fields' => [
                [
                    [
                        'field' => 'inputs.name', /* TODO: Создать поле в inputs */
                        'name' => 'Фамилия, имя и отчество',
                        'placeholder' => 'Алексеев Александр Иванович',
                        'type' => 'text',
                    ],
                    [
                        'field' => 'inputs.name', /* TODO: Создать поле в inputs */
                        'name' => 'Дата рождения',
                        'placeholder' => '20.05.1995',
                        'type' => 'date',
                    ],
                ],
                [
                    [
                        'field' => 'inputs.name', /* TODO: Создать поле в inputs */
                        'name' => 'Гражданство',
                        'type' => 'selection',
                        'values' => [
                            'Ученая степень / МВА',
                            'Несколько высших',
                            'Высшее',
                            'Незаконченное высшее',
                            'Среднее специальное',
                            'Среднее',
                            'Ниже среднего',
                        ]
                    ],
                    [
                        /* TODO: Создать поле в inputs */
                        'name' => 'Ваше образование',
                        'type' => 'selection',
                        'values' => [
                            'Российская федерация',
                            'Иное'
                        ]
                    ]
                ],
                [
                    [
                        /* TODO: Создать поле в inputs */
                        'name' => 'Семейное положение',
                        'type' => 'selection',
                        'values' => [
                            'Не в браке',
                            'В браке',
                            'Вдова / Вдовец',
                            'В разводе'
                        ]
                    ],
                    [
                        /* TODO: Создать поле в inputs */
                        'name' => 'Дети до 18 лет',
                        'type' => 'selection',
                        'values' => [
                            'Нет',
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6',
                        ]
                    ]
                ],
                [
                    /* TODO: Создать поле в inputs */
                    'name' => 'Ежемесячный доход',
                    'type' => 'text',
                ]
            ],
        ],
        [
            'title' => 'Место работы',
            'fields' => [],
        ],
        [
            'title' => 'Документы',
            'fields' => [],
        ],
        [
            'title' => 'Паспортные данные',
            'fields' => [],
        ],
    ];
@endphp
<div class="action-form-card">
{{--    <section class="action-form-card__form-active">--}}
{{--        <div class="action-form-card__form-booking">--}}
{{--            <div class="action-form-card__form-title">--}}
{{--                <p>Детали заявки</p>--}}
{{--            </div>--}}
{{--            @foreach($bookings as $item => $key)--}}
{{--                <div class="action-form-card__form-container">--}}
{{--                    <div class="action-form-card__title"><p>{{ $key['title'] }}</p></div>--}}
{{--                    <div class="action-form-card__description"><p>{{ $key['description'] }}</p></div>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--            @include('reservation.components._bottomActions')--}}
{{--        </div>--}}
{{--    </section>--}}

    <section class="action-form-card__form-active">
        <div class="action-form-card__form-order action-form-card__form-accordion-title">
            <div class="action-form-card__form-title">
                <p>Заполните заявку</p>
            </div>
        </div>
        <div class="action-accordion">
            @for($i=0; $i < count($accordions); $i++)
                <div class="action-accordion__item">
                    @if ($i != 0) <div class="action-accordion__item-top"></div> @endif
                    <div class="action-accordion__item-container">
                        <p>{{ $accordions[$i]['title'] }}</p>
                        <span class="active">Заполните</span>
                    </div>
                    <div class="action-accordion__item-control">
                        <svg width="7" height="10" viewBox="0 0 7 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 8.5L5 5L1 1.5" stroke="#EC7D3F" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <section class="action-accordion-user-info">
                    @include('reservation.components._formFields', ['fields' => $accordions[$i]['fields']])
                </section>
            @endfor
        </div>
    </section>

{{--    <section class="action-form-card__mortgage-solution"></section>--}}
{{--    <section class="action-form-card__mortgage-agreement"></section>--}}
</div>
