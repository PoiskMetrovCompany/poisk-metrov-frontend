<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Панель администратора Поиск метров</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
          crossorigin="anonymous">
    @vite([
        'resources/css/admin/style.css',
    ])
</head>
<body>
    <div class="container-fluid mt-5">
        <div class="container">
            <div class="row">
                <div class="col-2">NAN</div>
                <div class="col-8"></div>
                <div class="col-2">
                    <a href="#" class="btn btn-default">
                        Выйти из ЛК
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#494949" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="row">
                @include('admin.layouts.alert-form-layouts')
            </div>
            <div class="row">
                NAN
            </div>
            <div class="row">
                @include('admin.components.table')
            </div>
            <div class="row">
                NAN
            </div>
        </div>
    </div>
</body>
</html>
