<tr type="client-row" clientid="{{ $client['id'] }}">
    <td>{{ $client['name'] }}</td>
    <td>{{ $client['phone'] }}</td>
    <td>{{ $client['agent'] }}</td>
    <td colspan="2">{{ $client['date'] }}</td>
    <td colspan="2">
        @include('custom-elements.select', [
            'placeholder' => 'Статус',
            'defaultOption' => $client['status'],
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
    </td>
    <td align="right">
        <button>
            @include('icons.arrow-tailless')
        </button>
    </td>
</tr>
@isset($client['requests'])
    @include('agent.client.list.requests', ['requests' => $client['requests']])
@endisset
