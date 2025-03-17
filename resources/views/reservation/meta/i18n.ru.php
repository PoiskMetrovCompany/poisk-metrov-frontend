<?php

$title = 'Мои брони';
$apartment = 'Квартира-студия в ЖК Брусника, 30.2 м², этаж 9';
$apartmentPrice = '9 615 862 ₽';

$menuApartmentTitle = [
    'Жилой комплекс',
    'Девелопер',
    'Номер заявки',
    'Дата',
    'Кол-во комнат',
    'Стоимость',
    'За м2',
    'Уникальность',
    ' '
];
$chatName = 'Чат';
$menuLists = [
    [
        'data-section' => 'action-form-card__form-booking',
        'name' => 'Бронирование',
    ],
    [
        'data-section' => 'action-form-card__form-accordion',
        'name' => 'Заявка на ипотеку',
    ],
    [
        'data-section' => '',
        'name' => 'Ипотечное решение',
    ],
    [
        'data-section' => '',
        'name' => 'Договор',
    ],
    [
        'data-section' => '',
        'name' => $chatName,
    ],
];

//$apartmentsList = [
//    [
//        'name' => 'Пшеница',
//        'developer' => 'Брусника',
//        'order_id' => '39781369',
//        'data_tz' => '10.07.2024',
//        'count_rooms' => 'Студия',
//        'price' => '9 615 862 ₽',
//        'per_m2' => '136 756  ₽/м2',
//        'uniqueness' => '10.07.2024',
//        'details' => [
//            'href' => '#',
//            'description' => 'Подробнее'
//        ]
//    ],
//];

//$countApartments = count($apartmentList);

$users = ['client' => $client, 'manager' => $managerList[0] /* TODO: надо узнать сколько менеджеров может быть. Пока беру первого из списка. */];

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
        'key' => \Illuminate\Support\Str::uuid()->toString(),
        'fields' => [
            [
                [
                    'field' => 'inputs.fio',
                    'name' => 'Фамилия, имя и отчество',
                    'placeholder' => 'Алексеев Александр Иванович',
                    'inputType' => 'text',
                    'inputId'   => 'fio',
                    'inputName' => 'fio',
                    'value' => ''
                ],
                [
                    'field' => 'inputs.birth-date',
                    'name' => 'Дата рождения',
                    'placeholder' => '20.05.1995',
                    'inputType' => 'date',
                ],
            ],
            [
                [
                    'field' => 'inputs.dropdown.default',
                    'name' => 'Гражданство',
                    'inputType' => 'selection',
                    'inputId'   => 'citizenship',
                    'inputName' => 'citizenship',
                    'values' => [
                        'Российская федерация',
                        'Иное'
                    ]
                ],
                [
                    'field' => 'inputs.dropdown.default',
                    'name' => 'Ваше образование',
                    'inputType' => 'selection',
                    'inputId'   => 'education',
                    'inputName' => 'education',
                    'values' => [
                        'Ученая степень / МВА',
                        'Несколько высших',
                        'Высшее',
                        'Незаконченное высшее',
                        'Среднее специальное',
                        'Среднее',
                        'Ниже среднего',
                    ]
                ]
            ],
            [
                [
                    'field' => 'inputs.dropdown.default',
                    'name' => 'Семейное положение',
                    'inputType' => 'selection',
                    'inputId'   => 'marital_status',
                    'inputName' => 'marital_status',
                    'values' => [
                        'Не в браке',
                        'В браке',
                        'Вдова / Вдовец',
                        'В разводе'
                    ]
                ],
                [
                    'field' => 'inputs.dropdown.default',
                    'name' => 'Дети до 18 лет',
                    'inputType' => 'selection',
                    'inputId'   => 'presence_of_сhildren',
                    'inputName' => 'presence_of_сhildren',
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
                [
                    'field' => 'inputs.default',
                    'name' => 'Ежемесячный доход',
                    'inputType' => 'text',
                    'inputId'   => 'monthly_income',
                    'inputName' => 'monthly_income',
                    'inputIcons' => 'icon-inside'
                ],
                [

                ]
            ]
        ],
    ],
    [
        'title' => 'Место работы',
        'key' => \Illuminate\Support\Str::uuid()->toString(),
        'fields' => [
            [
                [
                    'field' => 'inputs.default',
                    'name' => 'Наименование организации',
                    'inputType' => 'text',
                    'inputId'   => 'work_company_name',
                    'inputName' => 'work_company_name',
                ],
            ],
            [
                [
                    'field' => 'inputs.default',
                    'name' => 'ИНН организации',
                    'inputType' => 'text',
                    'inputId'   => 'work_inn',
                    'inputName' => 'work_inn',
                ],
                [
                    'field' => 'inputs.default',
                    'name' => 'Телефон работодателя',
                    'inputType' => 'text',
                    'inputId'   => 'work_phone_employer',
                    'inputName' => 'work_phone_employer',
                ]
            ],
            [
                [
                    'field' => 'inputs.default',
                    'name' => 'Ваша должность',
                    'inputType' => 'text',
                    'inputId'   => 'work_job_title',
                    'inputName' => 'work_job_title',
                ],
                [

                ]
            ],
        ],
        'dropdown' => [
            'title' => 'Дополнительная информация',
            'key' => \Illuminate\Support\Str::uuid()->toString(),
            'fields' => [
                [
                    'field' => 'inputs.dropdown.default',
                    'name' => 'Вид трудового договора',
                    'inputType' => 'selection',
                    'inputId'   => 'work_sub_employment_contract',
                    'inputName' => 'work_sub_employment_contract',
                    'placeholder' => 'Без срока',
                    'values' => [
                        'Без срока',
                    ]
                ],
                [
                    'field' => 'inputs.dropdown.default',
                    'name' => 'Категория занимаемой должности',
                    'inputType' => 'selection',
                    'inputId'   => 'work_sub_position_category',
                    'inputName' => 'work_sub_position_category',
                    'placeholder' => 'Специалист, служащий',
                    'values' => [
                        'Специалист, служащий',
                    ]
                ],
                [
                    'field' => 'inputs.dropdown.default',
                    'name' => 'Количество сотрудников в организации',
                    'inputType' => 'selection',
                    'inputId'   => 'work_sub_count_developers',
                    'inputName' => 'work_sub_count_developers',
                    'placeholder' => 'Затрудняюсь ответить',
                    'values' => [
                        'Затрудняюсь ответить',
                    ]
                ],
                [
                    'field' => 'inputs.dropdown.default',
                    'name' => 'Категория занимаемой должности',
                    'inputType' => 'selection',
                    'inputId'   => 'work_sub_staging',
                    'inputName' => 'work_sub_staging',
                    'placeholder' => 'Общий трудовой стаж за 5 лет',
                    'values' => [
                        'Общий трудовой стаж за 5 лет',
                    ]
                ],
            ]
        ]
    ],
    [
        'title' => 'Документы',
        'key' => \Illuminate\Support\Str::uuid()->toString(),
        'leftTitleIcon' => 'folder-icon',
        'fields' => [],
        'dropdown' => [
            'title' => 'Подтверждение дохода',
            'key' => \Illuminate\Support\Str::uuid()->toString(),
            'fields' => [
                [
                    'field' => 'inputs.dropdown.default',
                    'name' => 'Документ о доходах',
                    'inputType' => 'selection',
                    'inputId'   => 'document_type',
                    'inputName' => 'work_sub_employment_contract',
                    'placeholder' => 'Справка о доходах',
                    'values' => [
                        'Документ о доходах',
                    ]
                ],
            ],
            [
                [
                    'field' => 'inputs.dropdown.default',
                    'name' => 'Вид трудового договора',
                    'inputType' => 'selection',
                    'inputId'   => 'work_sub_employment_contract',
                    'inputName' => 'work_sub_employment_contract',
                    'placeholder' => '_ _ . _ _ _ _',
                    'values' => [
                        'Месяц и год трудоустройства',
                    ]
                ],
            ]
        ]
    ],
    [
        'title' => 'Паспортные данные',
        'key' => \Illuminate\Support\Str::uuid()->toString(),
        'fields' => [
            [
                [
                    'field' => 'inputs.default',
                    'name' => 'Номер паспорта',
                    'placeholder' => '',
                    'inputType' => 'text',
                    'inputId'   => 'passport_num',
                    'inputName' => 'passport_num',
                ],
            ],
            [
                [
                    'field' => 'inputs.default',
                    'name' => 'Дата выдачи',
                    'placeholder' => '',
                    'inputType' => 'text',
                    'inputId'   => 'passport_date_access',
                    'inputName' => 'passport_date_access',
                ],
                [
                    'field' => 'inputs.default',
                    'name' => 'Код',
                    'placeholder' => '__-__',
                    'inputType' => 'text',
                    'inputId'   => 'passport_code',
                    'inputName' => 'passport_code',
                ],
            ],
            [
                [
                    'field' => 'inputs.default',
                    'name' => 'Наименование органа, выдавшего паспорт',
                    'placeholder' => '',
                    'inputType' => 'text',
                    'inputId'   => 'passport_accessor',
                    'inputName' => 'passport_accessor',
                ],
            ],
            [
                [
                    'field' => 'inputs.default',
                    'name' => 'Место рождения',
                    'placeholder' => '',
                    'inputType' => 'text',
                    'inputId'   => 'passport_place_of_birth',
                    'inputName' => 'passport_place_of_birth',
                ],
            ],
            [
                [
                    'field' => 'inputs.default',
                    'name' => 'Адрес регистрации на территории РФ',
                    'placeholder' => '',
                    'inputType' => 'text',
                    'inputId'   => 'passport_registration_address',
                    'inputName' => 'passport_registration_address',
                ],
            ],
        ],
    ],
];
$borrower = [];

foreach ($accordions as $accordion) {
    $accordion['key'] = \Illuminate\Support\Str::uuid()->toString();

    foreach ($accordion['fields'] as &$fieldGroup) {
        foreach ($fieldGroup as &$field) {
            if (isset($field['inputId']) && isset($field['inputName'])) {
                $field['inputId'] = 'borrower_' . $field['inputId'];
                $field['inputName'] = 'borrower_' . $field['inputName'];
            }
        }
    }

    if (isset($accordion['dropdown'])) {
        $accordion['dropdown']['key'] = \Illuminate\Support\Str::uuid()->toString();

        foreach ($accordion['dropdown']['fields'] as &$fieldGroup) {
            if (is_array($fieldGroup)) {
                foreach ($fieldGroup as &$field) {
                    if (isset($field['inputId']) && isset($field['inputName'])) {
                        $field['inputId'] = 'borrower_' . $field['inputId'];
                        $field['inputName'] = 'borrower_' . $field['inputName'];
                    }
                }
            }
        }
    }
    if ($accordion['title'] != 'Паспортные данные') {
        if ($accordion['title'] === 'Документы') {
            $accordion['passport'] = true;
            $borrower[] = $accordion;
        } else {
            $borrower[] = $accordion;
        }

    }
//    if ($accordion['title'] === 'Документы') {
//        $accordion['passport'] = true;
//        $borrower[] = $accordion;
//    }
}
