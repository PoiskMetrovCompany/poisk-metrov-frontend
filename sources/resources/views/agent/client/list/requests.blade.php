<tr type="request-row" clientid="{{ $requests[0]['client_id'] }}">
    <th>Жилой комплекс</th>
    <th>Застройщик</th>
    <th>Номер заявки</th>
    <th>Дата</th>
    <th>Время</th>
    <th>Статус заявки</th>
    <th>Уникальность</th>
    <th></th>
</tr>
@foreach ($requests as $request)
    <tr type="request-row" clientid="{{ $request['client_id'] }}">
        <td>{{ $request['residential_complex'] }}</td>
        <td>{{ $request['developer'] }}</td>
        <td>{{ $request['id'] }}</td>
        <td>{{ $request['date'] }}</td>
        <td>{{ $request['time'] }}</td>
        <td>{{ $request['status'] }}</td>
        <td>{{ $request['uniqueness'] }}</td>
        <td align="right">
            <a href="/">Подробнее</a>
        </td>
    </tr>
@endforeach
