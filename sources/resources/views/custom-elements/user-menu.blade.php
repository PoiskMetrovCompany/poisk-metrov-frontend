<user-menu>
    <header>
        <div>
            @include('custom-elements.parts.profile-placeholder')
        </div>
        <div>
            @auth
                <h6>Агент</h6>
                <h5>{{ $user->name }} {{ $user->surname }}</h5>
            @endauth
            @guest
                <h5>Посетитель</h5>
            @endguest
        </div>
        <button>
            @include('icons.close')
        </button>
    </header>
    <div>
        @include('custom-elements.contained-checkbox', [
            'id' => 'agents-options-toggle',
            'placeholder' => 'Работа с клиентом',
            'subclass' => 'toggle',
        ])
    </div>
    <ul>
        @include('custom-elements.parts.user-menu-option', [
            'icon' => 'settings',
            'optionText' => 'Мои заявки',
            'href' => '/',
        ])
        @include('custom-elements.parts.user-menu-option', [
            'icon' => 'settings',
            'optionText' => 'Тарифная сетка',
            'href' => '/',
            'clientOnly' => true,
        ])
        @include('custom-elements.parts.user-menu-option', [
            'icon' => 'settings',
            'optionText' => 'Мои презентации',
            'href' => '/',
        ])
        @include('custom-elements.parts.user-menu-option', [
            'icon' => 'settings',
            'optionText' => 'Бронирование',
            'href' => '/',
        ])
        @include('custom-elements.parts.user-menu-option', [
            'icon' => 'settings',
            'optionText' => 'Ипотека',
            'href' => '/',
        ])
        @include('custom-elements.parts.user-menu-option', [
            'icon' => 'settings',
            'optionText' => 'Подбор',
            'href' => '/',
        ])
        @include('custom-elements.parts.user-menu-option', [
            'icon' => 'settings',
            'optionText' => 'Сравнение',
            'href' => '/',
        ])
        @include('custom-elements.parts.user-menu-option', [
            'icon' => 'settings',
            'optionText' => 'Настройки профиля',
            'href' => '/',
        ])
        @include('custom-elements.parts.user-menu-option', [
            'icon' => 'exit',
            'optionText' => 'Выйти из личного кабинета',
            'href' => '/',
        ])
    </ul>
</user-menu>
