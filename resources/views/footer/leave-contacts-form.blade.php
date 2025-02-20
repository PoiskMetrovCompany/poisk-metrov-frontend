<form autocomplete="off" id="leave-contacts-form" name="leave-contacts-form" class="footer got-questions form container">
    @csrf
    <div class="footer got-questions form form-container">
        @include('inputs.name', ['style' => 'footer'])
        @include('inputs.phone', ['style' => 'footer'])
        <div class="footer got-questions form submit-button-container">
            <input type="submit" name="footer-submit-button" value=" "
                class="footer got-questions form submit-button">
            <div class="footer got-questions form submit-button-sub-container">
                <div class="footer got-questions form submit-button-text">Отправить</div>
                <img src="{{ Vite::asset('resources/assets/arrows/arrow-right-white.svg') }}">
            </div>
        </div>
    </div>
    @include('common.you-agree')
</form>
