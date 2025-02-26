<div id="reviews" class="reviews base-container">
    @vite('resources/js/gallery/reviewsLoader.js')
    <div class="reviews header">
        <div class="reviews title-flex">
            <div class="reviews title">
                Средняя оценка в соцсетях - 4,9*
            </div>
            <div id="reviews-gallery-buttons" class="arrow-buttons-container">
                @include('buttons.arrow-left')
                @include('buttons.arrow-right')
            </div>
        </div>
        <div class="reviews title-description">
            На основании отзывов более 300 клиентов
        </div>
    </div>
    <div class="divider"> </div>
    <div id="reviews-gallery" class="reviews review-container">
        <div class="reviews review frame">
            <div class="reviews review source-grid">
                <div class="reviews review reviewer-container">
                    <div class="reviews review reviewer-photo"></div>
                    <div class="reviews review reviewer">Наталья Ким</div>
                </div>
                <div class="reviews review source">
                    <img class="reviews review source-icon" src="{{ Vite::asset('resources/assets/2gis.svg') }}">
                </div>
            </div>
            <div class="reviews review description">
                Спасибо большое Геримович Олесе за профессионализм, внимательность. Быстро организовала встречу и показ
                квартиры, сопровождала на всех этапах сделки, внимательно следила за всеми изменениями и консультировала
                по всем вопросам. Благодаря ей, купила квартиру в очень короткий срок и очень этим довольна. Рекомендую
                как специалиста по недвижимости.
            </div>
        </div>
        <div class="reviews review frame">
            <div class="reviews review source-grid">
                <div class="reviews review reviewer-container">
                    <div class="reviews review reviewer-photo"></div>
                    <div class="reviews review reviewer">Станислав Должиков</div>
                </div>
                <div class="reviews review source">
                    <img class="reviews review source-icon" src="{{ Vite::asset('resources/assets/2gis.svg') }}">
                </div>
            </div>
            <div class="reviews review description">
                Отличная команда! Помогли с покупкой и оформлением капитального гаража, нестандартной на мой взгляд
                сделки. Ирина Ивановна креативная женщина которая знает свое дело! Сергей Нигай, человек который умеет
                объяснить и доставить информацию без лишних может быть, человек который работает на результат и ценит
                своих клиентов! Очень доволен что попал к таким людям, рекомендую. Всем спасибо!
            </div>
        </div>
        <div class="reviews review frame">
            <div class="reviews review source-grid">
                <div class="reviews review reviewer-container">
                    <div class="reviews review reviewer-photo"></div>
                    <div class="reviews review reviewer">Анна Новоселова</div>
                </div>
                <div class="reviews review source">
                    <img class="reviews review source-icon" src="{{ Vite::asset('resources/assets/2gis.svg') }}">
                </div>
            </div>
            <div class="reviews review description">
                Большое спасибо Бобырь Валерии за профессиональную поддержку в покупке квартиры. Мы с мужем долго
                думали, с какой стороны подойти к инвестиционной недвижимости. Благодаря Валерии все реализовалось
                оочень быстро! Пришли в офис на консультацию, сразу выбрали объект, получили бесценную поддержку Леры в
                оформлении ипотеки и проведении сделки. И все, ура - в декабре получаем ключи!
            </div>
        </div>
        <div class="reviews review frame">
            <div class="reviews review source-grid">
                <div class="reviews review reviewer-container">
                    <div class="reviews review reviewer-photo"></div>
                    <div class="reviews review reviewer">Антон Ганзя</div>
                </div>
                <div class="reviews review source">
                    <img class="reviews review source-icon" src="{{ Vite::asset('resources/assets/2gis.svg') }}">
                </div>
            </div>
            <div class="reviews review description">
                Спасибо Елизавете Третьяковой! Грамотный специалист, знает своё дело. Полное сопровождение на этапе
                выбора до сделки. Рекомендовала лучшие варианты, о которых даже не знал. Удобно работать с таким
                профессионалом, который понимает проблему клиента и находит решение. Однозначно рекомендую данное
                агентство, уверен, все специалисты здесь такие же компетентные и помогут решить любой запрос.
            </div>
        </div>
        <div class="reviews review frame">
            <div class="reviews review source-grid">
                <div class="reviews review reviewer-container">
                    <div class="reviews review reviewer-photo"></div>
                    <div class="reviews review reviewer">Павел Строков</div>
                </div>
                <div class="reviews review source">
                    <img class="reviews review source-icon" src="{{ Vite::asset('resources/assets/2gis.svg') }}">
                </div>
            </div>
            <div class="reviews review description">
                Риэлтор Потапов Владимир - лучший в своём деле! Помог найти то, что нужно, а не так как в большинстве
                риэлторских компаниях - лишь бы что-то впарить и комиссию сорвать. Огромное спасибо Володе за такой
                подход! Юрист Ирина очень грамотно и оперативно решает вопросы с документацией и сопровождением сделки.
                Спасибо компании Поиск Метров за таких специалистов!
            </div>
        </div>
        <div class="reviews review frame">
            <div class="reviews review source-grid">
                <div class="reviews review reviewer-container">
                    <div class="reviews review reviewer-photo"></div>
                    <div class="reviews review reviewer">Ольга Смолина</div>
                </div>
                <div class="reviews review source">
                    <img class="reviews review source-icon" src="{{ Vite::asset('resources/assets/2gis.svg') }}">
                </div>
            </div>
            <div class="reviews review description">
                Очень признательна за помощь и сопровождение в приобретении недвижимости Вяткину Сергею! Профессионально
                поставлена работа, большой опыт и обширные знания Сергея помогли мне приобрести жилье за короткий срок.
                Советую всем при выборе риэлтора обратить внимание на Сергея.
            </div>
        </div>
        <div class="reviews review frame">
            <div class="reviews review source-grid">
                <div class="reviews review reviewer-container">
                    <div class="reviews review reviewer-photo"></div>
                    <div class="reviews review reviewer">Марина Василевская</div>
                </div>
                <div class="reviews review source">
                    <img class="reviews review source-icon" src="{{ Vite::asset('resources/assets/2gis.svg') }}">
                </div>
            </div>
            <div class="reviews review description">
                Хочу выразить благодарность агенству недвижимости Авеню,а точнее Вяткину Сергею. Очень опытный, помогал
                с самого начала до завершения процесса. В любое время был на связи. Сделка прошла легко и быстро.
                Благодарю за такой хороший подход и профессионализм. Рекомендую данного специалиста!
            </div>
        </div>
        <div class="reviews review frame">
            <div class="reviews review source-grid">
                <div class="reviews review reviewer-container">
                    <div class="reviews review reviewer-photo"></div>
                    <div class="reviews review reviewer">Надежда Кокорина</div>
                </div>
                <div class="reviews review source">
                    <img class="reviews review source-icon" src="{{ Vite::asset('resources/assets/2gis.svg') }}">
                </div>
            </div>
            <div class="reviews review description">
                Большое спасибо риэлтору Анастасии Летовой. Она, кажется, сделала невозможное. Помогла с приобретением
                квартиры мечты. На выгодных условиях по ипотеке. И вот мы теперь ждём сдачи дома и когда нам вручат
                ключи от НАШЕЙ квартиры! Если вы хотите приобрести квартиру, но думаете что это сложно и невозможно.
                Обращайтесь к специалистам и они вам помогут и под скажут с чего начать.
            </div>
        </div>
    </div>
</div>
