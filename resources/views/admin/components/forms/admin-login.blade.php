<form id="admin-login-form" action="{{ route('admin.form.login') }}" method="POST">
    @csrf
    <div class="input-container">
        <label for="login">Логин</label>
        <input type="text" id="" name="admin-login" placeholder="Введите ваше имя" autocomplete="off">
    </div>
    <div class="input-container">
        <label for="password">Пароль</label>
        <input type="password" id="" name="admin-password" placeholder="Введите ваш пароль" autocomplete="off">
    </div>
    <div class="mb-3">
        <button type="submit" class="btn w-92-auto">Войти</button>
    </div>
</form>
