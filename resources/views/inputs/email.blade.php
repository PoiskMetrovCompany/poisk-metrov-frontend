<div class="input-container">
    <fieldset class="input-fieldset">
        <legend class="input-legend">{{ $emailInputTitle ?? "Email" }}</legend>
        <div class="input-wrapper">
            <input class="input-text" type="email" id="email" name="email" value={!! $value ?? "" !!}>
            <img src="{{Vite::asset('resources/assets/content/content-error.svg')}}" class="error-icon">
            <div class="icon action-close d20x20 grey3"></div>
        </div>
    </fieldset>
    <div class="input-error" id="input-error">Заполните поле корректно</div>
</div>