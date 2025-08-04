<client-table>
    <table>
        <thead>
            <tr>
                <th>Клиент</th>
                <th>Телефон</th>
                <th>Ответственный</th>
                <th colspan="2">Дата</th>
                <th colspan="2">Статус</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @include('agent.client.list.client-dropdown', [
                'client' => [
                    'id' => 1,
                    'name' => 'Гончаров Олег',
                    'phone' => '+7 (935) 153-53-65',
                    'agent' => 'Лотырева Ольга',
                    'date' => '22.06.2024',
                    'status' => 'Бронь',
                    'requests' => [
                        [
                            'residential_complex' => 'Пшеница',
                            'developer' => 'Брусника',
                            'id' => 3943155,
                            'date' => '11.11.2024',
                            'time' => '9:14',
                            'status' => 'Показ отменен',
                            'uniqueness' => 'до 10.12.2025',
                            'client_id' => 1,
                        ],
                        [
                            'residential_complex' => 'Европейский берег',
                            'developer' => 'Брусника',
                            'id' => 1542526,
                            'date' => '11.12.2024',
                            'time' => '19:43',
                            'status' => 'Встреча',
                            'uniqueness' => 'до 12.12.2025',
                            'client_id' => 1,
                        ],
                    ],
                ],
            ])
            @include('agent.client.list.client-dropdown', [
                'client' => [
                    'id' => 2,
                    'name' => 'Кузьмин Александр',
                    'phone' => '+7 (935) 165-62-62',
                    'agent' => 'Лотырева Ольга',
                    'date' => '08.07.2024',
                    'status' => 'Показ',
                ],
            ])
        </tbody>
    </table>
</client-table>
