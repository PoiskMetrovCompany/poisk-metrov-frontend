<div class="favorites card">
    <div class="favorites to-login" @auth style="display: none" @endauth>
        <div class="favorites item-title">
            Хотите сохранить свое избранное?
        </div>
        Чтобы сохранить список избранного и получить доступ к другим сервисам, войдите в личный кабинет.
        <div id="favorites-login" class="common-button wide">Личный кабинет</div>
    </div>
    <div class="favorites item-title" @guest style="display: none" @endguest>
        Скачать свой каталог с избранным
    </div>
    <div>Каталог с вашим избранным доступен для скачивания в формате pdf после регистрации.</div>
    <div class="favorites card buttons-grid" @guest style="display: none" @endguest>
        <a id="{{ $downloadButtonId }}" target="_blank" class="common-button">Скачать</a>
        <a class="common-button disabled" style="display: none">Отправить</a>
    </div>
</div>
