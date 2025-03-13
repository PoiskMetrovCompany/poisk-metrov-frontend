<div class="input-container">
    <fieldset class="input-fieldset">
        <legend class="input-legend">{{ $nameInputTitle ?? 'Дата рождения' }}<span
                class="red-highlight">{{ $required ?? '' }}</span></legend>
        <div class="input-wrapper">
            <input class="input-text {{ $style ?? '' }}" type="text" id="birth_date" name="birth_date" required
                   placeholder="{!! $placeholder ?? '01.01.2000' !!}">
            {{--            <img src="{{ Vite::asset('resources/assets/content/content-error.svg') }}" class="error-icon">--}}
            {{--            <div class="icon action-close d20x20 grey3"></div>--}}
        </div>
    </fieldset>
    {{--    <div class="input-error" id="input-error">Заполните поле корректно</div>--}}
</div>
