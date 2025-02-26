<div class="favorites empty container" id="empty-favorites">
    <div class="favorites header">
        <div class="favorites title">
            А тут ничего нет...
            <br>
            <span class="link-highlight">Выберите свою идеальную квартиру</span>
        </div>
        <div class="favorites empty grid">
            <div class="favorites empty item">
                <div class="icon content-smile orange d40x40"></div>
                <div>
                    <span class="link-highlight">Зарегистрируйся</span>
                    на сайте Поиск метров
                </div>
            </div>
            <div class="favorites empty item">
                <div class="icon content-binoculars orange d40x40"></div>
                <div>
                    <span><span class="link-highlight">Найдите</span> <span>для себя</span></span>
                    идеальную планировку
                </div>
            </div>
            <div class="favorites empty item">
                <div class="icon content-like orange d40x40"></div>
                <div>
                    <span>
                        <span class="link-highlight">Нажмите </span>
                        <img src="{{ Vite::asset('resources/assets/actions/action-like.svg') }}">
                        для добавления
                    </span>
                    <span>
                        квартиры в избранное
                    </span>
                </div>
            </div>
            <div class="favorites empty item">
                <div class="icon content-chart orange d40x40"></div>
                <div>
                    <span>Перейдите в избранное</span>
                    <span>для сравнения и выбора планировки</span>
                </div>
            </div>
        </div>
    </div>
    @include('common.best-offers')
</div>
