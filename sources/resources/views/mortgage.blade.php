@extends('document-layout', ['title' => 'Ипотека'])

@section('preload-images')
    @foreach ($preloadService->preloadFolder('assets/banks') as $file)
        <link rel="preload" as="image" href={{ Vite::asset($file) }}>
    @endforeach
@endsection

@section('content')
    <h1 class="title technical">Ипотека</h1>
    <div class="title-with-img container">
        <div class="title-with-img img-container" style="background-image: url('placeholders/placeholder-22.png')">
            <div class="title-with-img title">
                <span class="link-highlight">Поиск Метров</span> — найдем квартиру, поможем с ипотекой
            </div>
            <div class="title-with-img text">
                Мы сотрудничаем с большим количеством банков, а наши специалисты готовы добиться для вас выгодных условий
                по ипотеке!
            </div>
        </div>
        <div class="title-with-img text-small">
            Мы сотрудничаем с большим количеством банков, а наши специалисты готовы добиться для вас выгодных условий
            по ипотеке!
        </div>
    </div>
    <div class="mortgage types container">
        <div class="mortgage types card top">
            <div class="mortgage types photo">
                <img src="placeholders/placeholder-1.png">
            </div>
            <div class="mortgage types content">
                <div class="mortgage types header">
                    <div class="mortgage types title">
                        Семейная ипотека от 6%
                    </div>
                    <div class="mortgage types description">
                        Программа господдержки семей с детьми. В программе могут участвовать семьи, в которых с 1 января
                        2018 года и до 31 декабря 2022 года родился второй, третий или последующий ребёнок.
                    </div>
                </div>
                <div class="mortgage types info container">
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Базовая ставка</div>
                        <div class="mortgage types info value">6%</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Сумма кредита</div>
                        <div class="mortgage types info value">до 12 млн ₽</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Первоначальный взнос </div>
                        <div class="mortgage types info value">от 20.1%</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Срок</div>
                        <div class="mortgage types info value">до 30 лет</div>
                    </div>
                </div>
                <a id="card-calc-family" class="common-button" href="#calculator">
                    Рассчитать платёж
                </a>
            </div>
        </div>
        <div class="mortgage types card bottom">
            <div class="mortgage types photo">
                <img src="placeholders/placeholder-2.png">
            </div>
            <div class="mortgage types content">
                <div class="mortgage types header">
                    <div class="mortgage types title">
                        Ипотека с господдержкой от 8%
                    </div>
                    <div class="mortgage types description">
                        Кредит под сниженный процент для отдельных категорий заёмщиков, где разницу между рыночной
                        и льготной ставкой компенсирует государство.
                    </div>
                </div>
                <div class="mortgage types info container">
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Базовая ставка</div>
                        <div class="mortgage types info value">8%</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Сумма кредита</div>
                        <div class="mortgage types info value">до 12 млн ₽</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Первоначальный взнос </div>
                        <div class="mortgage types info value">от 20.1%</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Срок</div>
                        <div class="mortgage types info value">до 30 лет</div>
                    </div>
                </div>
                <a id="card-calc-state" class="common-button" href="#calculator">
                    Рассчитать платёж
                </a>
            </div>
        </div>
        <div class="mortgage types card horizontal">
            <div class="mortgage types content">
                <div class="mortgage types header">
                    <div class="mortgage types title">
                        Ипотека для IT-специалистов от 5%
                    </div>
                    <div class="mortgage types description">
                        Льготная программа для сотрудников компаний, осуществляющих деятельность в сфере информационных
                        технологий. Господдержка позволяет работникам получить ипотечный кредит с пониженной процентной
                        ставкой на квартиру от застройщика в готовой или строящейся новостройке.
                    </div>
                </div>
                <div class="mortgage types info container">
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Базовая ставка</div>
                        <div class="mortgage types info value">5%</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Сумма кредита</div>
                        <div class="mortgage types info value">до 18 млн ₽</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Первоначальный взнос</div>
                        <div class="mortgage types info value">от 20.1%</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Срок</div>
                        <div class="mortgage types info value">до 30 лет</div>
                    </div>
                </div>
                <a id="card-calc-it" class="common-button" href="#calculator">
                    Рассчитать платёж
                </a>
            </div>
            <div class="mortgage types photo">
                <img src="placeholders/placeholder-23.png">
            </div>
        </div>
        <div class="mortgage types card bottom">
            <div class="mortgage types photo">
                <img src="placeholders/placeholder-4.png">
            </div>
            <div class="mortgage types content">
                <div class="mortgage types header">
                    <div class="mortgage types title">
                        Сельская ипотека от 2.7%
                    </div>
                    <div class="mortgage types description">
                        Ипотечная программа, распространяющаяся на строительство или приобретение жилого дома на сельских
                        территориях.<br><br>
                    </div>
                </div>
                <div class="mortgage types info container">
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Базовая ставка</div>
                        <div class="mortgage types info value">2.7%</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Сумма кредита</div>
                        <div class="mortgage types info value">до 5 млн ₽</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Первоначальный взнос </div>
                        <div class="mortgage types info value">от 20.1%</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Срок</div>
                        <div class="mortgage types info value">до 25 лет</div>
                    </div>
                </div>
                <a id="card-calc-village" class="common-button" href="#calculator">
                    Рассчитать платёж
                </a>
            </div>
        </div>
        <div class="mortgage types card bottom">
            <div class="mortgage types photo">
                <img src="placeholders/placeholder-5.png">
            </div>
            <div class="mortgage types content">
                <div class="mortgage types header">
                    <div class="mortgage types title">
                        Ипотека на вторичное жильё от 15.6%
                    </div>
                    <div class="mortgage types description">
                        Кредитная программа, рассчитанная на покупку недвижимости, на которую уже зарегистрировано право
                        собственности.<br><br>
                    </div>
                </div>
                <div class="mortgage types info container">
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Базовая ставка</div>
                        <div class="mortgage types info value">15.6%</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Сумма кредита</div>
                        <div class="mortgage types info value">до 100 млн ₽</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Первоначальный взнос</div>
                        <div class="mortgage types info value">от 10.1%</div>
                    </div>
                    <div class="mortgage types info unit">
                        <div class="mortgage types info title">Срок</div>
                        <div class="mortgage types info value">до 30 лет</div>
                    </div>
                </div>
                <a id="card-calc-secondary" class="common-button" href="#calculator">
                    Рассчитать платёж
                </a>
            </div>
        </div>
    </div>
    @include('home-page.mortgage')
    <div id="partners" class="base-container">
        <div class="partners title">
            Наши банки - партнёры
        </div>
        <div class="banks container">
            <div class="banks card-with-icon">
                <div class="bank-icons vtb bg"></div>
            </div>
            <div class="banks card-with-icon">
                <div class="bank-icons alfa bg"></div>
            </div>
            <div class="banks card-with-icon">
                <div class="bank-icons raiffaisen bg"></div>
            </div>
            <div class="banks card-with-icon">
                <div class="bank-icons sberbank bg"></div>
            </div>
            <div class="banks card-with-icon">
                <div class="bank-icons gazprombank bg"></div>
            </div>
            <div class="banks card-with-icon">
                <div class="bank-icons psb bg"></div>
            </div>
            <div class="banks card-with-icon">
                <div class="bank-icons tkb bg"></div>
            </div>
            <div class="banks card-with-icon">
                <div class="bank-icons dom bg"></div>
            </div>
            <div class="banks card-with-icon">
                <div class="bank-icons sovkombank bg"></div>
            </div>
            <div class="banks card-with-icon">
                <div class="bank-icons uralsib bg"></div>
            </div>
        </div>
    </div>
    <div class="faq container">
        <div class="faq title">
            Часто задаваемые вопросы
        </div>
        <div class="faq main-content">
            <div id="faq-tabs" class="faq questions container">
                <div class="faq button">
                    <div>1.</div>
                    <div>У меня нет официального трудоустройства. Могут ли мне одобрить ипотеку?</div>
                </div>
                <div class="faq button">
                    <div>2.</div>
                    <div>Можно ли получить ипотеку, если раньше были просрочки по кредитам?</div>
                </div>
                <div class="faq button">
                    <div>3.</div>
                    <div>Какие ставки сейчас по ипотеке?</div>
                </div>
                <div class="faq button">
                    <div>4.</div>
                    <div>Я одна с ребенком. Дадут ли мне семейную ипотеку?</div>
                </div>
            </div>
            <div class="faq answer card">
                <div class="faq answer title">Ответ</div>
                <div id="faq-text" class="faq answer description">
                </div>
            </div>
        </div>
    </div>
    @include('menus.forms.learn-mortgage')
    @include('common.leave-request')
@endsection
