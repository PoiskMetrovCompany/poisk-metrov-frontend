<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анкета кандидата</title>
</head>
<body>

<h1>Кандидат на вакансию - {{ $data->title }}</h1>
<h1><strong>{{ $data->last_name }} {{ $data->first_name }} {{ $data->middle_name }}</strong></h1>

@if ($data->reason_for_changing_surnames)
    <p><strong>Причина смены фамилии:</strong> {{ $data->reason_for_changing_surnames }}</p>
@endif

<p><strong>Дата рождения:</strong> {{ $data->birth_date }}</p>
<p><strong>Страна рождения:</strong> {{ $data->country_birth }}</p>
<p><strong>Город рождения:</strong> {{ $data->city_birth }}</p>
<hr>

<p><strong>Мобильный телефон:</strong> {{ $data->mobile_phone_candidate }}</p>
<p><strong>Домашний телефон:</strong> {{ $data->home_phone_candidate }}</p>
<p><strong>Эл. почта:</strong> {{ $data->mail_candidate }}</p>
<hr>

<p><strong>ИНН:</strong> {{ $data->inn }}</p>
<p><strong>Паспорт (серия):</strong> {{ $data->passport_series }}</p>
<p><strong>Паспорт (номер):</strong> {{ $data->passport_number }}</p>
<p><strong>Паспорт (кем выдан):</strong> {{ $data->passport_issued }}</p>
<p><strong>Адрес регистрации:</strong> {{ $data->permanent_registration_address }}</p>
<p><strong>Временный адрес проживания:</strong> {{ $data->temporary_registration_address }}</p>
<p><strong>Фактический адрес проживания:</strong> {{ $data->actual_residence_address }}</p>
<hr>

<h3>Партнёр</h3>
@if (!empty($familyPartner))
    <p><strong>Имя:</strong> {{ $familyPartner['name'] }}</p>
    <p><strong>Возраст:</strong> {{ $familyPartner['age'] }}</p>
    <p><strong>Отношение:</strong> {{ $familyPartner['relation'] }}</p>
@else
    <p>Данные о партнёре отсутствуют.</p>
@endif

<h3>Дети старше 18 лет</h3>
@if (!empty($adultChildren) && is_array($adultChildren))
    @foreach($adultChildren as $item)
        <section>
            <p><strong>Имя:</strong> {{ $item['name'] ?? '' }}</p>
            <p><strong>Возраст:</strong> {{ $item['age'] ?? '' }}</p>
            <p><strong>Отношение:</strong> {{ $item['relation'] ?? '' }}</p>
        </section>
    @endforeach
@else
    <p>Нет детей старше 18 лет.</p>
@endif

<h3>Другие взрослые члены семьи</h3>
@if (!empty($adultFamilyMembers) && is_array($adultFamilyMembers))
    @foreach($adultFamilyMembers as $item)
        <section>
            <p><strong>Имя:</strong> {{ $item['name'] ?? '' }}</p>
            <p><strong>Возраст:</strong> {{ $item['age'] ?? '' }}</p>
            <p><strong>Отношение:</strong> {{ $item['relation'] ?? '' }}</p>
        </section>
    @endforeach
@else
    <p>Нет данных о других взрослых членах семьи.</p>
@endif

<hr>
<p><strong>Военнообязанный:</strong> {{ $data->serviceman }}</p>
<p><strong>Нарушения закона:</strong> {{ $data->law_breaker }}</p>
<p><strong>Организация:</strong> {{ $data->legal_entity }}</p>
<hr>
<p><strong>Комментарий:</strong> {{ $data->comment }}</p>

</body>
</html>
