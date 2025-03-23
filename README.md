<h1 style="color: #2c2c2c;">
    <img src="https://avatars.githubusercontent.com/u/180920011?s=200&v=4" style="width: 3.8%"> 
    ПОИСК МЕТРОВ
</h1>
<br>
<h3>Используемые технологии:</h3>
<ul>
    <li>
        <a href="#" style="color: #57bafb;">PHP 8.2 (Laravel 10)</a>
    </li>
    <li>
        <a href="#" style="color: #57bafb;">MySQL 8</a>
    </li>
    <li>
        <a href="#" style="color: #57bafb;">Npm 8.19.4</a>
    </li>
</ul>
<hr>
<h3 style="color: #2c2c2c;">Настройка окружения</h3>
<ul>
    <li>
        <p>
            <span style="color: #2c2c2c;">Создать/настроить конфиг: <small style="color: #57bafb;">(Как минимум надо настроить подключение к БД и настроить вебсокеты)</span><br>
            <b style="color: #2c2c2c;">(sudo) cp .example.env .env</b><br>
        </p>
    </li>
    <br />
    <li>
        <p>
            <span style="color: #2c2c2c;">Установить зависимости:</span><br>
            <b style="color: #2c2c2c;">(sudo) composer install</b><br>
        </p>
    </li>
    <br />
    <li>
        <span style="color: #2c2c2c;">Создать миграции: <small style="color: #57bafb;">(Нужно проверить есть ли база)</small></span><br>
        <b style="color: #2c2c2c;">(sudo) php artisan migrate</b><br>
    </li>
    <br />
    <li>
        <span style="color: #2c2c2c;">Сгенерировать CSRF токен:</span><br>
        <b style="color: #2c2c2c;">(sudo) php artisan key:generate</b><br>
    </li>
    <br />
    <li>
        <span style="color: #2c2c2c;">Установить права и группу на директорию с проектом:</span><br>
        <b style="color: #2c2c2c;">sudo chmod -R 777 $PWD</b><br>
        <b style="color: #2c2c2c;">sudo chown -R my_group:my_group $PWD</b><br>
    </li>
    <br />
    <li>
        <span style="color: #2c2c2c;">Создать ссылку на storage:</span><br>
        <b style="color: #2c2c2c;">(sudo) php artisan storage:link</b><br>
    </li>
    <br />
</ul>
<hr>
<h3 style="color: #2c2c2c;">Запуск проекта</h3>
<ul>
    <li>
        <p>
            <span style="color: #2c2c2c;">Проект запускается тремя командами:</span><br>
            <b style="color: #2c2c2c;">(sudo) php artisan serve</b><br>
            <b style="color: #2c2c2c;">(sudo) php artisan websocket:serve</b><br>
            <b style="color: #2c2c2c;">(sudo) npm run dev</b><br>
        </p>
    </li>
    <br />
</ul>
