<form action="#" style = "margin-top: 30px;">
    @csrf
    <div class="input-container" style="margin-bottom: 20px;">
        <label for="login" id="formLabel" class="formLabel">Логин</label>
        <input type="tel" name="login" id="login" class="formInput" placeholder="Введите логин">
        <div class="checkmark-icon" id="checkmarkIcon">
            <svg viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
        </div>
    </div>
    <div class="input-container">
        <label for="password" id="password" class="formLabel">Пароль</label>
        <input type="password" name="password" id="password" class="formInput" placeholder="Введите пароль">
        <div class="checkmark-icon" id="checkmarkIcon">
            <svg viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
        </div>
    </div>

    <button class="formBtn btn-active" disabled="true">
        Войти
    </button><br>
</form>
