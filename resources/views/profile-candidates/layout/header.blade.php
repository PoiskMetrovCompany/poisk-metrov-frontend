@php
    $role = 'security-guard';
@endphp

@if ($role === 'security-guard')
    <header>
        <div class="formRow justify-space-between w-60">
            <div style= "display: flex; align-items: center;">
                <img id = "nonTextImg" src="/img/ logo без текста.png" alt="Логотип компании Поиск Метров">
                <h5 id="city">Город: <span>Новосибирск</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 9L12 15L18 9" />
                    </svg>
                </h5>
            </div>
            <div class="w-60" style="display: flex; align-items: center; justify-content: center; gap: 30px">
                <span class="active">Кандидаты</span>
                <span>Настройки</span>
            </div>
            <div style="display: flex; justify-content: space-between; min-width: 250px;">
                <button id = "notifBtn"><img src="/img/ring.png" alt="Уведомлений нет"></button>
                <button id = "exitBtn">Выйти из ЛК <img src="/img/arowRight.png" alt="Стрелочка вправо"></button>
            </div>
        </div>
    </header>
@else
    <header>
        <img src="/img/Logo с текстом.png" alt="Картинка с логотипом агенства и подписью Поиск метров">
    </header>
@endif
