<div class="header profile-buttons">
    <a href="/favorites" class="header like-wrapper">
        <div class="header round-button">
            @include('icons.like')
            @php
                $favCount = $favoritesService->countFavorites();
            @endphp
            <div class="header counter" @if ($favCount == 0) style="display: none" @endif>
                {{ $favCount }}
            </div>
        </div>
        <div class="not-md">Избранное</div>
    </a>
    @include('favorites.added-popup')
    @auth
        <a href="javascript:void(0)" class="header like-wrapper" id="{{ $profileButtonId }}">
            <div class="header round-button">
                @include('icons.profile')
            </div>
            <div>{{ $user->name }}</div>
        </a>
        @include('menus.profile-menu')
    @endauth
    @guest
        <a href="javascript:void(0)" class="header like-wrapper" id="{{ $loginButtonId }}">
            <div class="header round-button">
                @include('icons.profile')
            </div>
            <div class="not-mobile">Войти</div>
        </a>
    @endguest
</div>
