<div class="input-container">
    <fieldset class="input-fieldset">
        <legend class="input-legend">{{ $surnameInputTitle ?? "Фамилия"}}</legend>
        <div class="input-wrapper">
            <input class="input-text {{$style ?? ''}}" type="text" id="surname" name="surname" value="{{$value ?? ''}}" placeholder={!! $placeholder ?? "Введите&nbsp;вашу&nbsp;фамилию" !!} >
            <img src="{{Vite::asset('resources/assets/content/content-error.svg')}}" class="error-icon">
            <div class="icon action-close d20x20 grey3"></div>
        </div>
    </fieldset>
    <div class="input-error" id="input-error">Заполните поле корректно</div>
</div>