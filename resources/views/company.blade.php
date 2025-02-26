@extends('document-layout', ['title' => 'О&nbsp;компании'])

@section('pagescript')
    @vite('resources/js/aboutUs/aboutUs.js')
@endsection

@section('content')
    <div class="company top-half">
        <div class="company photo-header" style="background-image: url('placeholders/placeholder-18.jpg')">
            <div class="company header-title-container">
                <div class="company header-title">
                    <span class="link-highlight">Поиск Метров</span> — бесплатный сервис бронирования новостроек
                </div>
                <div class="company header-text large">
                    Мы много лет сотрудничаем с самыми надёжными застройщиками, с которыми у нас сложились партнёрские
                    отношения. Это позволяет нам предоставлять для вас бесплатные консультации, а также помощь в поиске.
                </div>
            </div>
            <div class="company header-cards container">
                <div class="company header-cards card">
                    <div class="icon content-bank orange"></div>
                    <div class="company header-cards text">
                        <div class="company header-cards title">20+</div>
                        Банков-партнеров
                    </div>
                </div>
                <div class="company header-cards card">
                    <div class="icon content-house orange"></div>
                    <div class="company header-cards text">
                        <div class="company header-cards title">300+</div>
                        Объектов недвижимости
                    </div>
                </div>
                <div class="company header-cards card">
                    <div class="icon content-worker orange"></div>
                    <div class="company header-cards text">
                        <div class="company header-cards title">150+</div>
                        Сотрудников
                    </div>
                </div>
                <div class="company header-cards card">
                    <div class="icon content-smile orange"></div>
                    <div class="company header-cards text">
                        <div class="company header-cards title">1000+</div>
                        Успешных сделок
                    </div>
                </div>
            </div>
        </div>
        <div class="company header-text small">
            Мы много лет сотрудничаем с самыми надёжными застройщиками, с которыми у нас сложились партнёрские отношения.
            Это позволяет нам предоставлять для вас бесплатные консультации, а также помощь в поиске.
        </div>
        <div class="company header-cards container small">
            <div class="company header-cards card">
                <div class="icon content-bank orange"></div>
                <div class="company header-cards text">
                    <div class="company header-cards title">20+</div>
                    Банков-партнеров
                </div>
            </div>
            <div class="company header-cards card">
                <div class="icon content-house orange"></div>
                <div class="company header-cards text">
                    <div class="company header-cards title">300+</div>
                    Объектов недвижимости
                </div>
            </div>
            <div class="company header-cards card">
                <div class="icon content-worker orange"></div>
                <div class="company header-cards text">
                    <div class="company header-cards title">150+</div>
                    Сотрудников
                </div>
            </div>
            <div class="company header-cards card">
                <div class="icon content-smile orange"></div>
                <div class="company header-cards text">
                    <div class="company header-cards title">1000+</div>
                    Успешных сделок
                </div>
            </div>
        </div>
    </div>
    <div class="company description">
        <h1 class="title first">О компании</h1>
        АН «Поиск Метров» - самая динамично развивающаяся компания на рынке новостроек г. Новосибирска. История компании
        началась в 2020 году. В 2023 году произошел ребрендинг. На конец 2023 года компания насчитывает более 150
        сотрудников в г. Новосибирск. А также компания вышла на рынок г. Санкт-Петербурга. По результатам 2023 года АН
        «Поиск Метров» занимает лидирующее место по продажам новостроек в Новосибирске. АН «Поиск Метров» - компания полного
        цикла, начиная от получения ипотечного решения, подбора недвижимости, покупки, продажи квартир, домов, заканчивая
        полным сопровождением сделки.
    </div>
    <div class="company our-workers container" id="company-employees"
        style = "{{ $selectedCity == 'novosibirsk' ? 'display: grid' : 'display: none' }}">
        <div class="company our-workers container">
            <div class="title first">Специалисты по недвижимости в {{ $cityService->where[$selectedCity] }}</div>
            <div class="company our-workers tabs" id="employees-tabs">
                <div class="tab">Офис на ул. Кошурникова</div>
                <div class="tab">Офис на ул. Дуси Ковальчук</div>
            </div>
            @include('common.employees', ['city' => $selectedCity])
        </div>
    </div>
    <div class="company amenities">
        <div class="title first">Преимущества</div>
        <div class="company amenities main-grid">
            <div class="company amenities first-row">
                <div class="company amenity-card card img" style="background-image: url('placeholders/placeholder-19.jpg')">
                    <div class="company amenity-card shadow">
                        <div class="company amenity-card white-text">Подберем лучшие <br class="company line small"> условия
                            по ипотеке</div>
                    </div>
                </div>
                <div class="company amenity-card card white centered">
                    <div class="company amenity-card container-with-img">
                        <div class="icon content-discount orange"></div>
                        <div class="company amenity-card text">
                            <div class="company amenity-card title">Недвижимость <br class="company line big"> без <br
                                    class="company line small"> комиссий</div>
                            Банков-партнеров
                        </div>
                    </div>
                </div>
            </div>
            <div class="company amenities grid">
                <div class="company amenities left-grid">
                    <div class="company amenities second-row">
                        <div class="company amenity-card card white">
                            <div class="company amenity-card container-with-img">
                                <div class="icon content-excursion orange"></div>
                                <div class="company amenity-card text">
                                    <div class="company amenity-card title">Экскурсии по новостройкам</div>
                                    Банков-партнеров
                                </div>
                            </div>
                        </div>
                        <div class="company amenity-card card white">
                            <div class="company amenity-card text">
                                <div class="company amenity-card title">Берем на себя все вопросы по сопровождению сделки
                                </div>
                                Банков-партнеров
                            </div>
                        </div>
                    </div>
                    <div class="company amenities last-row">
                        <div class="company amenity-card card img small"
                            style="background-image: url('placeholders/placeholder-20.jpg'">
                            <div class="company amenity-card shadow">
                                <div class="company amenity-card black-text">Найдем лучшие объекты для жизни <br
                                        class="company line xl"> и инвестиций</div>
                            </div>
                        </div>
                        <div class="company amenity-card card white centered">
                            <div class="company amenity-card container-with-img">
                                <div class="icon content-discount orange"></div>
                                <div class="company amenity-card text">
                                    <div class="company amenity-card title">Недвижимость <br class="company line big"> без
                                        комиссий</div>
                                    Банков-партнеров
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="company amenity-card card white">
                    <div class="company amenity-card container-with-img">
                        <div class="company amenity-card building">
                            <img src="placeholders/content-building.svg">
                        </div>
                        <div class="company amenity-card prices-small"></div>
                        <div class="company amenity-card text">
                            <div class="company amenity-card title">Только <br class="company line big"> актуальные цены
                            </div>
                            Банков-партнеров
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('common.leave-request')
@endsection
