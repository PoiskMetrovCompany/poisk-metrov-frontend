<div class="profile-menu container">
    <div class="profile-menu header">
        <div class="profile-menu title-with-close">
            <div class="profile-menu title">
                Пользователь
            </div>
            <div class="sub-menus close">
                <div class="icon action-close orange d20x20"></div>
            </div>
        </div>
        {{ $user->surname }} {{ $user->name }}
    </div>
    <div class="profile-menu menu-content">
        <a href="/profile">
            <div class="profile-menu item">
                <div class="icon settings orange d24x24"></div>
                Настройки профиля
            </div>
        </a>
        <div class="divider"></div>
        <div id="{{ $logoutButtonId ?? 'logout-button' }}" class="profile-menu item">
            <div class="icon exit orange d24x24"></div>
            Выйти из личного кабинета
        </div>
    </div>
</div>
