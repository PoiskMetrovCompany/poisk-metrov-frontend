@extends('document-layout', ['title' => 'Продать'])

@section('content')
<div class="sell container">
    <h1 class="title technical">Продать</h1>
    <div class="sell header-container" style="background-image: url('placeholders/placeholder-21.png')">
        <div class="sell title">
            Хотите продать свою <br class="sell line"> недвижимость?
        </div>
        <a href="#sell-building-base" class="common-button">Оставить заявку</a>
    </div>
    <div id="how-we-work" class="how-we-work base-container">
        <div class="how-we-work left-side">
            <div class="title">
                Как мы работаем?
            </div>
        </div>
        <div class="how-we-work right-side">
            <div class="how-we-work section">
                <div class="how-we-work section-left">
                    <div class="how-we-work section-circle">
                        01
                    </div>
                    <div class="how-we-work section-line"> </div>
                </div>
                <div class="how-we-work section-right">
                    <div class="how-we-work section-title">
                        Оформление заявки
                    </div>
                    <div class="how-we-work section-description">
                        Оставьте заявку на сайте для первичной консультации с нашим специалистом.
                    </div>
                </div>
            </div>
            <div class="how-we-work section">
                <div class="how-we-work section-left">
                    <div class="how-we-work section-circle">
                        02
                    </div>
                    <div class="how-we-work section-line"> </div>
                </div>
                <div class="how-we-work section-right">
                    <div class="how-we-work section-title">
                        Оценка недвижимости
                    </div>
                    <div class="how-we-work section-description">
                        Проведем предварительную оценку рыночной стоимости вашей неджижимости, согласуем цену и условия
                        сделки.
                    </div>
                </div>
            </div>
            <div class="how-we-work section">
                <div class="how-we-work section-left">
                    <div class="how-we-work section-circle">
                        03
                    </div>
                    <div class="how-we-work section-line"> </div>
                </div>
                <div class="how-we-work section-right">
                    <div class="how-we-work section-title">
                        Подготовка документов
                    </div>
                    <div class="how-we-work section-description">
                        Мы подготовим все документы, необходимые для дальнейшей продажи имущества.
                    </div>
                </div>
            </div>
            <div class="how-we-work section">
                <div class="how-we-work section-left">
                    <div class="how-we-work section-circle">
                        04
                    </div>
                    <div class="how-we-work section-line"> </div>
                </div>
                <div class="how-we-work section-right">
                    <div class="how-we-work section-title">
                        Фотосъемка недвижимости
                    </div>
                    <div class="how-we-work section-description">
                        Проведем профессиональную фотосъемку недвижимости, покажем все преимущества вашего объекта.
                    </div>
                </div>
            </div>
            <div class="how-we-work section">
                <div class="how-we-work section-left">
                    <div class="how-we-work section-circle">
                        05
                    </div>
                    <div class="how-we-work section-line"> </div>
                </div>
                <div class="how-we-work section-right">
                    <div class="how-we-work section-title">
                        Рекламная кампания
                    </div>
                    <div class="how-we-work section-description">
                        Создадим качественную рекламную кампанию, которая сократит время поиска покупателя.
                    </div>
                </div>
            </div>
            <div class="how-we-work section">
                <div class="how-we-work section-left">
                    <div class="how-we-work section-circle">
                        06
                    </div>
                    <div class="how-we-work section-line"> </div>
                </div>
                <div class="how-we-work section-right">
                    <div class="how-we-work section-title">
                        Самостоятельно проведем показ
                    </div>
                    <div class="how-we-work section-description">
                        Назначим удобное время, защитим интересы продавца.
                    </div>
                </div>
            </div>
            <div class="how-we-work section">
                <div class="how-we-work section-left">
                    <div class="how-we-work section-circle">
                        07
                    </div>
                    <div class="how-we-work section-line"> </div>
                </div>
                <div class="how-we-work section-right">
                    <div class="how-we-work section-title">
                        Оформим сделку
                    </div>
                    <div class="how-we-work section-description">
                        Обеспечим юридическое сопровождение сделки.
                    </div>
                </div>
            </div>
            <div class="how-we-work section">
                <div class="how-we-work section-left">
                    <div class="how-we-work section-circle">
                        08
                    </div>
                </div>
                <div class="how-we-work section-right">
                    <div class="how-we-work section-title">
                        Оплата услуги
                    </div>
                    <div class="how-we-work section-description">
                        У нас фиксированная стоимость услуги без дополнительных комиссий.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sell signup">
        <div id="sell-building-base" class="signup base-container">
            <div class="signup half">
                <div class="signup header">
                    <div class="signup title">Оставьте заявку</div>
                </div>
            </div>
            <form autocomplete="off" id="sell-building-form" class="signup half form">
                @csrf
                <div class="signup top-input">
                    @include('dropdown.custom-dropdown', [
                    'id' => 'building-type',
                    'title' => 'Тип недвижимости',
                    'items' => ['Квартира', 'Дом, коттедж', 'Коммерческая', 'Комната', 'Гараж, парковка'],
                    'preview' => 'Не выбран'
                    ])
                    @include('dropdown.custom-dropdown', [
                        'id' => 'service-type',
                        'title' => 'Выбрать услугу',
                        'items' => ['Продать', 'Оценить'],
                        'preview' => 'Не выбрана'
                    ])
                    @include('inputs.name')
                    @include('inputs.last-name')
                    @include('inputs.middle-name')
                    @include('inputs.phone')
                </div>
                @include('common.personal-info-agreement')
            </form>
        </div>
    </div>
</div>
@endsection
