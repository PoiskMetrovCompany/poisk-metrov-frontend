@extends('profile-candidates.layout.app')

@section('content')
    <section>
        <div class="center-card big">
            <div class = "fixedMenu">
                <div class = "navArea">
                    <div class="yellowSelect" id="customSelect">
                        <div class="select-trigger" id="selectTrigger">
                            Новая анкета
                            <div class="trigger-icons">
                            </div>
                        </div>
                        <div class="select-dropdown" id="selectDropdown">
                            <div class="select-option selected" data-value="new">Новая анкета</div>
                            <div class="select-option" data-value="needs-work">Нужна доработка</div>
                            <div class="select-option" data-value="checked">Проверен</div>
                            <div class="select-option" data-value="rejected">Отклонен</div>
                        </div>
                    </div>
                    <a class = "activeLink" href="#">Общие сведенья</a>
                    <a href="#">Паспотные данные</a>
                    <a href="#">Состав семьи</a>
                    <a href="#">Юридический статус</a>
                </div>
                <div class = "navArea" style="margin-top: 3rem;">
                    <textarea name="comment" id="commentArea" placeholder="Написать комментарий"></textarea>
                    <button id = "addComment">Оставить коментарий</button>
                </div>
            </div>
            <p style="position: absolute; top: -2.7rem; left: 0; display: flex; align-items: center; gap: 1rem; cursor: pointer;">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.5 3L2 7.5L6.5 12M2.5 7.5H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Вернуться к списку
            </p>

            <div class="formRow justify-space-between">
                <h3 style = "width: auto; display: flex; align-items: center;">Александров Александр Александрович, 26 лет</h3>
                <p>Дата подачи</p>
            </div>
            <span id = "line"></span>
            <div class="formRow justify-space-between">
                <h4 style="width: auto; display: flex; align-items: center; margin-top: 0;">Агент по недвижимости</h4>
                <p>10.07.2024, 15:47</p>
            </div>
            <div id="surnameChangeReason" class="toggle-block" style="width: 100%;">
                <div class="formRow">
                    <div class="input-container">
                        <label for="reasonOfChange" id="formLabel" class="formLabel">Причина изменения фамилии</label>
                        <input type="text" name="reasonOfChange" id="reasonOfChange" class="formInput big" placeholder="Опишите, почему поменяли фамилию" value="Замужество" readonly>
                    </div>
                </div>
            </div>
            <div class="formRow justify-space-between">
                <div class="input-container w-49">
                    <label for="birthDate" id="formLabel" class="formLabel">Дата рождения</label>
                    <input style="width: 100%;" type="text" name="birthDate" id="birthDate" class="formInput" placeholder="01.01.1990" value="15.03.1995" readonly>
                </div>

                <div class="input-container w-49">
                    <label for="birthPlace" id="formLabel" class="formLabel">Место рождения</label>
                    <input style="width: 100%;" type="text" name="birthPlace" id="birthPlace" class="formInput" placeholder="Страна и город" value="Российская Федерация, г. Москва" readonly>
                </div>
            </div>
            <div class="formRow justify-space-between">
                <div class="input-container w-49">
                    <label for="mobileNumber" id="mobileNumber" class="formLabel">Мобильный телефон</label>
                    <input style="width: 100%;" type="tel" name="mobileNumber" id="mobileNumber" class="formInput" placeholder="+7(999)999-99-99" value="+7(905)123-45-67" readonly>
                </div>

                <div class="input-container w-49">
                    <label for="domesticNumber" id="domesticNumber" class="formLabel">Домашний телефон</label>
                    <input style="width: 100%;" type="tel" name="domesticNumber" id="domesticNumber" class="formInput" placeholder="999 999" value="495 234-56-78" readonly>
                </div>
            </div>
            <div class="formRow justify-space-between">
                <div class="input-container w-49">
                    <label for="email" id="email" class="formLabel">E-mail</label>
                    <input style="width: 100%;" type="email" name="email" id="email" class="formInput" value = "rabotnik@gmail.com" placeholder="example@gmail.com" readonly>
                </div>

                <div class="input-container w-49">
                    <label for="INN" id="INN" class="formLabel">ИНН</label>
                    <input style="width: 100%;" type="number" name="INN" id="INN" class="formInput" value = "324716976901" placeholder="123456789012" readonly>
                </div>
            </div>

            <div class="formRow" style="margin-top: 50px;">
                <h3>Паспортные данные</h3>
            </div>

            <div class="formRow justify-space-between">
                <div class="input-container w-49">
                    <label for="passwordSeriaNumber" id="passwordSeriaNumber" class="formLabel">Серия и номер </label>
                    <input style="width: 100%;" type="tel" name="passwordSeriaNumber" id="passwordSeriaNumber" class="formInput" placeholder="1234 567890" value="4514 123456" readonly>
                </div>

                <div class="input-container w-49">
                    <label for="dateOfIssue" id="dateOfIssue" class="formLabel">Дата выдачи</label>
                    <input style="width: 100%;" type="tel" name="dateOfIssue" id="dateOfIssue" class="formInput" placeholder="01.01.1990" value="20.03.2015" readonly>
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="issuedBy" id="issuedBy" class="formLabel">Кем выдан</label>
                    <input style="width: 100%;" type="text" name="issuedBy" id="issuedBy" class="formInput" placeholder="ОФУМС России" value="УФМС России по г. Москве" readonly>
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="adressOfPermanentReg" id="adressOfPermanentReg" class="formLabel">Адрес постоянной регистрации</label>
                    <input style="width: 100%;" type="text" name="adressOfPermanentReg" id="adressOfPermanentReg" class="formInput" placeholder="Адрес постоянной регистрации" value="г. Москва, ул. Тверская, д. 15, кв. 42" readonly>
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="adressOfTemporaryReg" id="adressOfTemporaryReg" class="formLabel">Адрес временной регистрации</label>
                    <input style="width: 100%;" type="text" name="adressOfTemporaryReg" id="adressOfTemporaryReg" class="formInput" placeholder="Адрес временной регистрации" value="г. Санкт-Петербург, пр. Невский, д. 28, кв. 15" readonly>
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="adressOfFactialLiving" id="adressOfFactialLiving" class="formLabel">Адрес фактического проживания</label>
                    <input style="width: 100%;" type="text" name="adressOfFactialLiving" id="adressOfFactialLiving" class="formInput" placeholder="Адрес фактического проживания" value="г. Санкт-Петербург, пр. Невский, д. 28, кв. 15" readonly>
                </div>
            </div>
            <div class="formRow" style="margin-top: 50px;">
                <h3>Состав семьи</h3>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="maritalStatus" id="maritalStatus" class="formLabel">Семейное положение</label>
                    <input style="width: 100%;" type="text" name="maritalStatus" id="maritalStatus" class="formInput" placeholder="Адрес фактического проживания" value="Состоит в зарегистрированном браке" readonly>
                </div>
            </div>
            <div class="formRow">
                <table class="inputTable showTable">
                    <tr>
                        <td colspan="2">
                            <input type="text" name="FIOSuprug" placeholder="ФИО супруга(-и)" value="Иванов Сергей Петрович" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="dateOfBirthTable" placeholder="Дата рождения" value="12.07.1993" readonly>
                        </td>
                        <td>
                            <input type="tel" name="phoneNumberTable" placeholder="Телефон" value="+7(906)987-65-43" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="placeOfStudy" placeholder="Место учебы/работы, рабочий телефон" value="ООО «Стройком», +7(495)123-45-67" readonly>
                        </td>
                        <td>
                            <input type="text" name="placeOfLiving" placeholder="Место проживания" value="г. Санкт-Петербург, пр. Невский, д. 28, кв. 15" readonly>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="formRow flex-direction-column">
                <h3>Данные  совершеннолетнего ребенка</h3>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="hasGraduatedChild" id="hasGraduatedChild" class="formLabel">Наличие совершеннолетних детей</label>
                    <input style="width: 100%;" type="text" name="hasGraduatedChild" id="hasGraduatedChild" class="formInput" placeholder="Адрес фактического проживания" value="Есть" readonly>
                </div>
            </div>
            <div id="doesHaveAdultChildren" class="toggle-block" style="width: 100%;">
                <div class="formRow showTable">
                    <table class="inputTable showTable">
                        <tr>
                            <td colspan="2">
                                <input type="text" name="FIOChildren1" placeholder="ФИО ребенка" value="Иванова Анна Сергеевна" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="dateOfBirthChildren1" placeholder="Дата рождения" value="05.04.2005" readonly>
                            </td>
                            <td>
                                <input type="tel" name="phoneNumberChildren1" placeholder="Телефон" value="+7(925)456-78-90" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="placeOfStudyChildren1" placeholder="Место учебы/работы, рабочий телефон" value="МГУ, факультет психологии" readonly>
                            </td>
                            <td>
                                <input type="text" name="placeOfLivingChildren1" placeholder="Место проживания" value="г. Москва, общежитие МГУ" readonly>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="formRow flex-direction-column">
                <h3>2. Члены семьи старше 18 лет</h3>
            </div>


            <div id="doesHaveAdultRelative" class="toggle-block" style="width: 100%;">
                <!-- Существующая таблица -->
                <div class="formRow">
                    <table class="inputTable showTable">
                        <tr>
                            <td colspan="2">
                                <input type="text" name="FIORelative1" placeholder="Степень родства, ФИО члена семьи" value="Мать, Петрова Елена Владимировна" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="dateOfBirthRelative1" placeholder="Дата рождения" value="23.09.1970" readonly>
                            </td>
                            <td>
                                <input type="tel" name="phoneNumberRelative1" placeholder="Телефон" value="+7(916)234-56-78" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="placeOfStudyRelative1" placeholder="Место учебы/работы, рабочий телефон" value="Пенсионер" readonly>
                            </td>
                            <td>
                                <input type="text" name="placeOfLivingRelative1" placeholder="Место проживания" value="г. Москва, ул. Тверская, д. 15, кв. 42" readonly>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="formRow flex-direction-column" style="margin-top: 50px;">
                <h3>Юридический статус</h3>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="militaryDuty" id="militaryDuty" class="formLabel">Статус военнообязанного</label>
                    <input type="text" name="militaryDuty" id="militaryDuty" class="formInput big"  value="Является военнообязанным" readonly>
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="reasonOfChange" id="formLabel" class="formLabel">Наличие уголовной или административной ответсвенности</label>
                    <input type="text" name="reasonOfChange" id="reasonOfChange" class="formInput big" value="Да, имеется" readonly>
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="whyPrisoner" id="whyPrisoner" class="formLabel">Причины привлечения к уголовной или административной ответственности</label>
                    <input type="text" name="whyPrisoner" id="whyPrisoner" class="formInput big" value="Захотелось" readonly>
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="isLegalEntity" id="isLegalEntity" class="formLabel">Является или нет (со-)учередителем юридического лица</label>
                    <input type="text" name="isLegalEntity" id="isLegalEntity" class="formInput big" value="Да, является" readonly>
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="LegalEntityActivity" id="LegalEntityActivity" class="formLabel">Является или нет (со-)учередителем юридического лица</label>
                    <input type="text" name="LegalEntityActivity" id="isLegalEntity" class="formInput big" value="Готовим шаурму" readonly>
                </div>
            </div>
    </section>

    <script>
        const customSelect = document.getElementById('customSelect');
        const selectTrigger = document.getElementById('selectTrigger');
        const selectDropdown = document.getElementById('selectDropdown');
        const selectOptions = selectDropdown.querySelectorAll('.select-option');

        // Открытие/закрытие селектора
        selectTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            customSelect.classList.toggle('open');
        });

        // Выбор опции
        selectOptions.forEach(option => {
            option.addEventListener('click', () => {
                const value = option.getAttribute('data-value');
                const text = option.textContent;

                // Обновляем текст триггера
                const triggerText = selectTrigger.childNodes[0];
                triggerText.textContent = text;

                // Обновляем выбранную опцию
                selectOptions.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');

                // Закрываем селектор
                customSelect.classList.remove('open');
            });
        });

        // Закрытие при клике вне селектора
        document.addEventListener('click', (e) => {
            if (!customSelect.contains(e.target)) {
                customSelect.classList.remove('open');
            }
        });

        // Закрытие по Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                customSelect.classList.remove('open');
            }
        });
    </script>
@endsection
