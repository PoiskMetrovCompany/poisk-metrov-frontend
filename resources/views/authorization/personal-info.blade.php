<div id="personal-info" class="auth-popup background">
    <div class="auth-popup base-container">
        @include('authorization.popup-header')
        <form autocomplete="off" id="personal-info-form" class="auth-popup form" onsubmit="return false;">
            @csrf
            <div class="login text-container">
                Заполните короткую анкету ниже - она необходима для корректной работы личного кабинета.
            </div>
            @include('inputs.name')
            @include('inputs.surname')
            <input type="submit" class="peinag button active" value="Сохранить">
        </form>
    </div>
</div>
