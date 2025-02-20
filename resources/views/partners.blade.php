@extends('document-layout', ['title' => 'Партнёрам'])

@section('content')
    <h1 class="title technical">Партнёрам</h1>
    <div class="partners container">
        <div class="title-with-img container">
            <div class="title-with-img img-container partners"
                style="background-image: url('placeholders/placeholder-17.jpg')">
                <div class="title-with-img title">
                    <span class="link-highlight">Поиск Метров</span> — найдем квартиру, поможем с ипотекой
                </div>
                <div class="title-with-img text">
                    Вы можете передать клиента в наше сопровождение, если он планирует переезд в другой город.
                    Мы предусматриваем агентское вознаграждение за каждого переданного клиента!
                </div>
            </div>
            <div class="title-with-img text-small">
                Вы можете передать клиента в наше сопровождение, если он планирует переезд в другой город.
                Мы предусматриваем агентское вознаграждение за каждого переданного клиента!
            </div>
        </div>
        <div id="how-we-work" class="how-we-work base-container">
            <div class="how-we-work left-side">
                <div class="title">
                    Как мы работаем с партнёрами?
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
                            Мы принимаем клиентов, которые планируют переезд в города: Новосибирск, Сочи, Санкт-Петербург,
                            Москва, Анапа, Туапсе, Дубай. Чтобы передать вашего клиента, вы должны заполнить форму обратной
                            связи.
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
                            Уточнение информации
                        </div>
                        <div class="how-we-work section-description">
                            После обсуждения деталей с клиентом, мы передадим вам обратную связь с записями переговоров
                            и переписок, чтобы вы всегда могли оставаться в курсе происходящего.
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
                            Подбор оптимального варианта для клиента
                        </div>
                        <div class="how-we-work section-description">
                            С учётом предоставленной информации о клиенте и его нуждах, наши специалисты подберут для него
                            оптимальный вариант недвижимости, предложат варианты и сопроводят сделку.
                        </div>
                    </div>
                </div>
                <div class="how-we-work section">
                    <div class="how-we-work section-left">
                        <div class="how-we-work section-circle">
                            04
                        </div>
                    </div>
                    <div class="how-we-work section-right">
                        <div class="how-we-work section-title">
                            Вознаграждение
                        </div>
                        <div id="transfer-client-card" class="how-we-work section-description">
                            После успешного завершения сделки, мы перечислим вам партнёрское вознаграждение
                            за предоставленного клиента. Это может быть определённая сумма денег или комиссия от сделки,
                            которую мы согласуем заранее.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="transfer-client base-container">
            <div class="transfer-client left container">
                <div class="transfer-client left title">
                    Оформите заявку на передачу клиента
                </div>
                <div class="transfer-client left description">
                    Заполните все необходимые данные и мы свяжется с вами для уточнения деталей
                </div>
            </div>
            <form autocomplete="off" id="transfer-client" class="transfer-client right form">
                @csrf
                <div class="transfer-client right fields">
                    <div class="transfer-client right title">
                        Информация об агенте
                    </div>
                    @include('inputs.name')
                    @include('inputs.phone')
                </div>
                <div class="transfer-client right fields">
                    <div class="transfer-client right title">
                        Информация о клиенте
                    </div>
                    @include('inputs.name', [
                        'nameInputTitle' => 'Имя клиента',
                        'placeholder' => 'Введите&nbsp;имя&nbsp;клиента',
                    ])
                    @include('inputs.phone', ['phoneInputTitle' => 'Телефон клиента'])
                    @include('dropdown.custom-dropdown', [
                        'id' => 'city',
                        'title' => 'Город, в котором планируется покупка',
                        'items' => $cityService->cityNames,
                        'preview' => 'Не выбран',
                    ])
                </div>
                @include('common.personal-info-agreement')
            </form>
        </div>
    </div>
@endsection
