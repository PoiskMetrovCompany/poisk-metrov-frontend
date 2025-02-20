<div class="input-container">
    <fieldset class="input-fieldset">
        <legend class="input-legend">{{ $phoneInputTitle ?? 'Ваш телефон' }}<span
                class="red-highlight">{{ $required ?? '' }}</span></legend>
        <div class="input-wrapper">
            <input class="input-text {{ $style ?? '' }}" type="tel" id="phone" name="phone"
                placeholder={!! $placeholder ?? '+7' !!} required @guest value={!! $value ?? '' !!} @endguest
                @auth value={!! $value ?? "'{$user->phone}'" !!} @endauth>
            <img src="{{ Vite::asset('resources/assets/content/content-error.svg') }}" class="error-icon">
            @include('icons.icon', ['iconClass' => 'action-close', 'iconColor' => 'grey3'])
        </div>
    </fieldset>
    <div class="input-error" id="input-error">Проверьте корректность номера</div>
</div>
