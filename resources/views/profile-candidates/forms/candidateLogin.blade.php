<form action="#">
    <div class="input-container">
        <label for="phoneNumber" id="formLabel" class="formLabel">Телефон</label>
        <input type="tel" name="phoneNumber" id="phoneNumber" class="formInput" placeholder="Введите номер">
    </div>

    <button id="getCodeBtn" class="formBtn btn-inactive" disabled="true">
        Получить код
    </button><br>
    <div class="checkboxRow" id="checkboxRow">
        <label class="custom-checkbox" for="personalData">
            <input type="checkbox" name="personalData" id="personalData">
            <span class="checkmark"></span>
        </label>
        <label for="personalData">Я даю согласие на обработку <span>своих персональных данных</span></label>
    </div>
</form>
