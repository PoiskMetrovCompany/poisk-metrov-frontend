@extends('profile-candidates.layout.app')

@section('content')
    <section >
        <div class="center-card big">
            <h1>Общие сведения</h1>
            <p>Мы не передаём эти данные третьим лицам и используем их только для целей адаптации и сопровождения кандидатов</p>

            <div class="formRow">
                <div class="input-container">
                    <label for="Vacansy" id="formLabel" class="formLabel">Вакансия</label>
                    <select name="Vacansy" id="phoneNumber" class="formInput big" style="display: none;">
                        <option value="" disabled selected>Выберите вакансию, на которую подаетесь</option>
                        <option value="Агент по недвижимости (риелтор)">Агент по недвижимости (риелтор)</option>
                        <option value="Помощник риелтора">Помощник риелтора</option>
                        <option value="Менеджер по аренде недвижимости">Менеджер по аренде недвижимости</option>
                        <option value="Специалист по работе с клиентами (CRM-менеджер)">Специалист по работе с клиентами (CRM-менеджер)</option>
                        <option value="Маркетолог в риелторское агентство">Маркетолог в риелторское агентство</option>
                        <option value="Оценщик недвижимости">Оценщик недвижимости</option>
                        <option value="Юрист по недвижимости">Юрист по недвижимости</option>
                        <option value="Руководитель отдела продаж">Руководитель отдела продаж</option>
                        <option value="Координатор сделок">Координатор сделок</option>
                        <option value="Развиватель бизнеса (бизнес-партнер)">Развиватель бизнеса (бизнес-партнер)</option>
                    </select>
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="FIO" id="formLabel" class="formLabel">ФИО</label>
                    <input type="text" name="FIO" id="phoneNumber" class="formInput big" placeholder="Иванов Иван Иванович">
                </div>
            </div>
            <div class="formRow justify-flex-start">
                <div class="input-container big">
                    <label class="custom-radio">
                        <input type="radio" name="surnameChanged" id="surnameYesChanged" checked>
                        <span class="radiomark"></span>
                        Я менял(-а) фамилию
                    </label>

                    <label class="custom-radio">
                        <input type="radio" name="surnameChanged" id="surnameNotChanged">
                        <span class="radiomark"></span>
                        Я не менял(-а) фамилию
                    </label>
                </div>
            </div>
            <div id="surnameChangeReason" class="toggle-block" style="width: 100%;">
                <div class="formRow">
                    <div class="input-container">
                        <label for="reasonOfChange" id="formLabel" class="formLabel">Причина изменения фамилии</label>
                        <input type="text" name="reasonOfChange" id="reasonOfChange" class="formInput big" placeholder="Опишите, почему поменяли фамилию">
                    </div>
                </div>
            </div>
            <div class="formRow justify-space-between">
                <div class="input-container w-49">
                    <label for="birthDate" id="formLabel" class="formLabel">Дата рождения</label>
                    <input style="width: 100%;" type="text" name="birthDate" id="birthDate" class="formInput" placeholder="01.01.1990">
                </div>

                <div class="input-container w-49">
                    <label for="birthPlace" id="formLabel" class="formLabel">Место рождения</label>
                    <input style="width: 100%;" type="text" name="birthPlace" id="birthPlace" class="formInput" placeholder="Страна и город">
                </div>
            </div>
            <div class="formRow justify-space-between">
                <div class="input-container w-49">
                    <label for="mobileNumber" id="mobileNumber" class="formLabel">Мобильный телефон</label>
                    <input style="width: 100%;" type="tel" name="mobileNumber" id="mobileNumber" class="formInput" placeholder="+7(999)999-99-99">
                </div>

                <div class="input-container w-49">
                    <label for="domesticNumber" id="domesticNumber" class="formLabel">Домашний телефон</label>
                    <input style="width: 100%;" type="tel" name="domesticNumber" id="domesticNumber" class="formInput" placeholder="999 999">
                </div>
            </div>
            <div class="formRow justify-space-between">
                <div class="input-container w-49">
                    <label for="email" id="email" class="formLabel">E-mail</label>
                    <input style="width: 100%;" type="email" name="email" id="email" class="formInput" placeholder="example@gmail.com">
                </div>

                <div class="input-container w-49">
                    <label for="INN" id="INN" class="formLabel">ИНН</label>
                    <input style="width: 100%;" type="number" name="INN" id="INN" class="formInput" placeholder="123456789012">
                </div>
            </div>

            <div class="formRow" style="margin-top: 50px;">
                <h3>Паспортные данные</h3>
            </div>

            <div class="formRow justify-space-between">
                <div class="input-container w-49">
                    <label for="passwordSeriaNumber" id="passwordSeriaNumber" class="formLabel">Серия и номер </label>
                    <input style="width: 100%;" type="tel" name="passwordSeriaNumber" id="passwordSeriaNumber" class="formInput" placeholder="1234 567890">
                </div>

                <div class="input-container w-49">
                    <label for="dateOfIssue" id="dateOfIssue" class="formLabel">Дата выдачи</label>
                    <input style="width: 100%;" type="tel" name="dateOfIssue" id="dateOfIssue" class="formInput" placeholder="01.01.1990">
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="issuedBy" id="issuedBy" class="formLabel">Кем выдан</label>
                    <input style="width: 100%;" type="text" name="issuedBy" id="issuedBy" class="formInput" placeholder="ОФУМС России">
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="adressOfPermanentReg" id="adressOfPermanentReg" class="formLabel">Адрес постоянной регистрации</label>
                    <input style="width: 100%;" type="text" name="adressOfPermanentReg" id="adressOfPermanentReg" class="formInput" placeholder="Адрес постоянной регистрации">
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="adressOfTemporaryReg" id="adressOfTemporaryReg" class="formLabel">Адрес временной регистрации</label>
                    <input style="width: 100%;" type="text" name="adressOfTemporaryReg" id="adressOfTemporaryReg" class="formInput" placeholder="Адрес временной регистрации">
                </div>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="adressOfFactialLiving" id="adressOfFactialLiving" class="formLabel">Адрес фактического проживания</label>
                    <input style="width: 100%;" type="text" name="adressOfFactialLiving" id="adressOfFactialLiving" class="formInput" placeholder="Адрес фактического проживания">
                </div>
            </div>
            <div class="formRow flex-direction-column" style="margin-top: 50px;">
                <h3>Состав семьи</h3>
                <h4>Заполните эти данные, чтобы мы могли предложить вам подходящие условия</h4>
            </div>
            <div class="formRow">
                <div class="input-container">
                    <label for="maritalStatus" id="formLabel" class="formLabel">Семейное положение</label>
                    <select name="maritalStatus" id="maritalStatusSelect" class="formInput big" style="display: none;">
                        <option value="" disabled selected>Выберите ваше семейное положение</option>
                        <option value="Не женат/Не замужем">Не женат/Не замужем</option>
                        <option value="Женат/Замужем">Женат/Замужем</option>
                        <option value="В разводе">В разводе</option>
                        <option value="Вдовец/Вдова">Вдовец/Вдова</option>
                        <option value="Гражданский брак">Гражданский брак</option>
                    </select>
                </div>
            </div>
            <div class="formRow">
                <table class="inputTable">
                    <caption class="tableLabel">
                        Данные супруга(-и)
                    </caption>
                    <tr>
                        <td colspan="2">
                            <input type="text" name="FIOSuprug" placeholder="ФИО супруга(-и)">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="dateOfBirthTable" placeholder="Дата рождения">
                        </td>
                        <td>
                            <input type="tel" name="phoneNumberTable" placeholder="Телефон">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="placeOfStudy" placeholder="Место учебы/работы, рабочий телефон">
                        </td>
                        <td>
                            <input type="text" name="placeOfLiving" placeholder="Место проживания">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="formRow flex-direction-column">
                <h3>1. Дети старше 18 лет</h3>
            </div>
            <div class="formRow justify-flex-start">
                <div class="input-container big">
                    <label class="custom-radio">
                        <input type="radio" name="haveChildren" id="haveChildren" checked>
                        <span class="radiomark"></span>
                        У меня есть дети старше 18 лет
                    </label>

                    <label class="custom-radio">
                        <input type="radio" name="haveChildren" id="dontHaveChildren">
                        <span class="radiomark"></span>
                        У меня нет детей старше 18 лет
                    </label>
                </div>
            </div>
            <div id="doesHaveAdultChildren" class="toggle-block" style="width: 100%;">
                <div class="formRow">
                    <table class="inputTable">
                        <caption class="tableLabel">
                            Данные совершеннолетнего ребенка
                        </caption>
                        <tr>
                            <td colspan="2">
                                <input type="text" name="FIOChildren1" placeholder="ФИО ребенка">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="dateOfBirthChildren1" placeholder="Дата рождения">
                            </td>
                            <td>
                                <input type="tel" name="phoneNumberChildren1" placeholder="Телефон">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="placeOfStudyChildren1" placeholder="Место учебы/работы, рабочий телефон">
                            </td>
                            <td>
                                <input type="text" name="placeOfLivingChildren1" placeholder="Место проживания">
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="formRow" style="margin-bottom: 0;">
                    <button class="bigFormButton">
                        <div class="textCont"></div>
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Добавить совершеннолетнего ребенка
                    </button>
                </div>
                <div class="formRow justify-flex-start" style="margin-top: 10px;">
                    <p style="margin-top: 0;">Добавьте всех имеющихся детей</p>
                </div>
            </div>

            <div class="formRow flex-direction-column">
                <h3>2. Члены семьи старше 18 лет</h3>
            </div>

            <div class="formRow justify-flex-start">
                <div class="input-container big">
                    <label class="custom-radio">
                        <input type="radio" name="familyMembers" id="haveFamilyMembers" checked>
                        <span class="radiomark"></span>
                        У меня есть члены семьи (родители/братья/сестры) старше 18 лет
                    </label><br>

                    <label class="custom-radio">
                        <input type="radio" name="familyMembers" id="dontHaveFamilyMembers">
                        <span class="radiomark"></span>
                        У меня нет членов семьи (родители/братья/сестры) старше 18 лет
                    </label>
                </div>
            </div>

            <div id="doesHaveAdultRelative" class="toggle-block" style="width: 100%;">
                <!-- Существующая таблица -->
                <div class="formRow">
                    <table class="inputTable">
                        <caption class="tableLabel">
                            Данные члена семьи
                        </caption>
                        <tr>
                            <td colspan="2">
                                <input type="text" name="FIORelative1" placeholder="Степень родства, ФИО члена семьи">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="dateOfBirthRelative1" placeholder="Дата рождения">
                            </td>
                            <td>
                                <input type="tel" name="phoneNumberRelative1" placeholder="Телефон">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="placeOfStudyRelative1" placeholder="Место учебы/работы, рабочий телефон">
                            </td>
                            <td>
                                <input type="text" name="placeOfLivingRelative1" placeholder="Место проживания">
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="formRow" style="margin-bottom: 0;">
                    <button class="bigFormButton" id="addRelative">
                        <div class="textCont"></div>
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Добавить члена семьи
                    </button>
                </div>
                <div class="formRow justify-flex-start" style="margin-top: 10px; margin-left: 30px;">
                    <p style="margin-top: 0;">Добавьте всех ближайших совершеннолетних членов семьи: родителей, братьев/сестер</p>
                </div>
            </div>


            <div class="formRow flex-direction-column" style="margin-top: 50px;">
                <h3>Юридический статус</h3>
                <h4>Ответьте на следующие вопросы, которые помогут нам оценить ваше соответствие вакансии </h4>
            </div>


            <div class="formRow justify-flex-start">
                <p style="margin-top: 0; color:#181817; font-size: 18px">1. Являетесь ли военнообязанным(-ой)?</p>
            </div>
            <div class="formRow justify-flex-start" style="margin-top: 0; font-size: 18px">
                <div class="input-container big">
                    <label class="custom-radio">
                        <input type="radio" name="millitaryDuty" id="YesMillitary" checked>
                        <span class="radiomark"></span>
                        Да, являюсь
                    </label>

                    <label class="custom-radio">
                        <input type="radio" name="millitaryDuty" id="NoMillitary">
                        <span class="radiomark"></span>
                        Нет, не являюсь
                    </label>
                </div>
            </div>
            <div class="formRow justify-flex-start" style="margin-top: 50px;">
                <p style="margin-top: 0; color:#181817; font-size: 18px">2. Привлекались ли вы когда-либо к уголовной ответственности?</p>
            </div>
            <div class="formRow justify-flex-start" style="margin-top: 0; font-size: 18px">
                <div class="input-container big">
                    <label class="custom-radio">
                        <input type="radio" name="criminalResponsibility" id="YesCriminal">
                        <span class="radiomark"></span>
                        Да, привлекался
                    </label>

                    <label class="custom-radio">
                        <input type="radio" name="criminalResponsibility" id="NoCriminal" checked>
                        <span class="radiomark"></span>
                        Нет, не привлекался
                    </label>
                </div>
            </div>

            <div id="doesCriminalResponsibility" class="toggle-block hidden" style="width: 100%;">
                <div class="formRow">
                    <div class="input-container">
                        <label for="whyPrisoner" id="whyPrisoner" class="formLabel">Причины привлечения</label>
                        <input style="width: 100%;" type="text" name="whyPrisoner" id="whyPrisoner" class="formInput" placeholder="Опишите, за что привлекались к ответственности">
                    </div>
                </div>
            </div>


            <div class="formRow justify-flex-start" style="margin-top: 50px;">
                <p style="margin-top: 0; color:#181817; font-size: 18px">3. Являетесь ли вы (со-)учредителем юридического лица?</p>
            </div>
            <div class="formRow justify-flex-start" style="margin-top: 0; font-size: 18px">
                <div class="input-container big">
                    <label class="custom-radio">
                        <input type="radio" name="legalEntity" id="YesLegalEntity">
                        <span class="radiomark"></span>
                        Да, являюсь
                    </label>

                    <label class="custom-radio">
                        <input type="radio" name="legalEntity" id="NoLegalEntity" checked>
                        <span class="radiomark"></span>
                        Нет, не являюсь
                    </label>
                </div>
            </div>

            <div id="doesLegalEntity" class="toggle-block hidden" style="width: 100%;">
                <div class="formRow">
                    <div class="input-container">
                        <label for="LegalEntityActivity" id="LegalEntityActivity" class="formLabel">Укажите наименование и сферу деятельности</label>
                        <input style="width: 100%;" type="text" name="LegalEntity" id="LegalEntity" class="formInput" placeholder="Наименование и сфера деятельности юрлица">
                    </div>
                </div>
            </div>

            <div class="checkboxRow" style="max-width: none; align-items: center;" id="checkboxRow">
                <label class="custom-checkbox" for="personalData">
                    <input type="checkbox" name="personalData" id="personalData">
                    <span class="checkmark"></span>
                </label>
                <label for="personalData">Я даю согласие на обработку <span>своих персональных данных</span></label>
            </div>
            <div class="formRow" style="margin-top: 0px">
                <button id="getCodeBtn" class="formBtn btn-inactive" disabled="true">
                    Отправить анкету
                </button>
            </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Функция для управления видимостью блоков
            function setupToggleBlock(radioYesId, radioNoId, blockId, initialShow = true) {
                const radioYes = document.getElementById(radioYesId);
                const radioNo = document.getElementById(radioNoId);
                const block = document.getElementById(blockId);

                function toggleBlock() {
                    if (radioYes.checked) {
                        block.classList.remove('hidden');
                    } else {
                        block.classList.add('hidden');
                    }
                }

                radioYes.addEventListener('change', toggleBlock);
                radioNo.addEventListener('change', toggleBlock);

                // Инициализация состояния
                if (initialShow) {
                    block.classList.remove('hidden');
                }
                toggleBlock();
            }

            // Новый код для добавления таблиц
            let relativeCounter = 1; // Счётчик для членов семьи
            let childrenCounter = 1; // Счётчик для детей

            // Функция генерации новой таблицы члена семьи
            function createRelativeTable(index) {
                const tableDiv = document.createElement('div');
                tableDiv.className = 'formRow table-container';

                tableDiv.innerHTML = `
            <table class="inputTable">
                <caption class="tableLabel">Данные члена семьи</caption>
                <tr>
                    <td colspan="2">
                        <input type="text" name="FIORelative${index}" placeholder="Степень родства, ФИО члена семьи">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="dateOfBirthRelative${index}" placeholder="Дата рождения">
                    </td>
                    <td>
                        <input type="tel" name="phoneNumberRelative${index}" placeholder="Телефон">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="placeOfStudyRelative${index}" placeholder="Место учебы/работы, рабочий телефон">
                    </td>
                    <td>
                        <input type="text" name="placeOfLivingRelative${index}" placeholder="Место проживания">
                    </td>
                </tr>
            </table>
        `;

                return tableDiv;
            }

            // Функция генерации новой таблицы ребенка
            function createChildrenTable(index) {
                const tableDiv = document.createElement('div');
                tableDiv.className = 'formRow table-container';

                tableDiv.innerHTML = `
            <table class="inputTable">
                <caption class="tableLabel">Данные совершеннолетнего ребенка</caption>
                <tr>
                    <td colspan="2">
                        <input type="text" name="FIOChildren${index}" placeholder="ФИО ребенка">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="dateOfBirthChildren${index}" placeholder="Дата рождения">
                    </td>
                    <td>
                        <input type="tel" name="phoneNumberChildren${index}" placeholder="Телефон">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="placeOfStudyChildren${index}" placeholder="Место учебы/работы, рабочий телефон">
                    </td>
                    <td>
                        <input type="text" name="placeOfLivingChildren${index}" placeholder="Место проживания">
                    </td>
                </tr>
            </table>
        `;

                return tableDiv;
            }

            // Функция для плавного добавления элемента
            function addElementWithAnimation(newElement, buttonRow) {
                // Начальное состояние (невидимое)
                newElement.style.opacity = '0';
                newElement.style.transform = 'translateY(-20px)';
                newElement.style.maxHeight = '0';
                newElement.style.overflow = 'hidden';
                newElement.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';

                // Вставляем элемент перед кнопкой
                buttonRow.parentNode.insertBefore(newElement, buttonRow);

                // Запускаем анимацию после небольшой задержки, чтобы браузер успел применить начальные стили
                setTimeout(() => {
                    newElement.style.opacity = '1';
                    newElement.style.transform = 'translateY(0)';
                    newElement.style.maxHeight = '216px'; // Примерная высота таблицы
                }, 10);
            }

            // Обработчик кнопки "Добавить члена семьи"
            document.getElementById('addRelative').addEventListener('click', function() {
                relativeCounter++;
                const newTable = createRelativeTable(relativeCounter);
                const buttonRow = this.parentElement;

                addElementWithAnimation(newTable, buttonRow);
            });

            // Обработчик кнопки "Добавить совершеннолетнего ребенка"
            document.querySelector('#doesHaveAdultChildren button.bigFormButton').addEventListener('click', function() {
                childrenCounter++;
                const newTable = createChildrenTable(childrenCounter);
                const buttonRow = this.parentElement;

                addElementWithAnimation(newTable, buttonRow);
            });

            // Настройка всех переключаемых блоков
            setupToggleBlock('surnameYesChanged', 'surnameNotChanged', 'surnameChangeReason');
            setupToggleBlock('haveChildren', 'dontHaveChildren', 'doesHaveAdultChildren');
            setupToggleBlock('haveFamilyMembers', 'dontHaveFamilyMembers', 'doesHaveAdultRelative');
            setupToggleBlock('YesCriminal', 'NoCriminal', 'doesCriminalResponsibility', false);
            setupToggleBlock('YesLegalEntity', 'NoLegalEntity', 'doesLegalEntity', false);

            // Функция для создания кастомного select
            function createCustomSelect(selectId, options) {
                const selectElement = document.getElementById(selectId);
                if (!selectElement) return;

                // Создаем контейнер для кастомного select
                const customSelect = document.createElement('div');
                customSelect.className = 'custom-select';
                customSelect.style.width = '100%';

                // Создаем выбранный элемент
                const selectSelected = document.createElement('div');
                selectSelected.className = 'select-selected';
                selectSelected.innerHTML = selectElement.getAttribute('placeholder') || 'Выберите вариант';

                // Создаем контейнер для вариантов
                const selectItems = document.createElement('div');
                selectItems.className = 'select-items select-hide';

                // Добавляем варианты
                options.forEach(option => {
                    const optionDiv = document.createElement('div');
                    optionDiv.innerHTML = option;
                    optionDiv.addEventListener('click', function() {
                        // При клике на вариант
                        const sameAsSelected = this.parentNode.querySelector('.same-as-selected');
                        if (sameAsSelected) {
                            sameAsSelected.classList.remove('same-as-selected');
                        }
                        this.classList.add('same-as-selected');
                        selectSelected.innerHTML = this.innerHTML;
                        selectElement.value = option;

                        // Закрываем список
                        selectItems.classList.add('select-hide');
                        selectSelected.classList.remove('select-arrow-active');

                        // Если это select семейного положения, обрабатываем изменение
                        if (selectId === 'maritalStatusSelect') {
                            toggleSpouseTable(option);
                        }
                    });
                    selectItems.appendChild(optionDiv);
                });

                // Добавляем элементы в DOM
                customSelect.appendChild(selectSelected);
                customSelect.appendChild(selectItems);

                // Заменяем оригинальный select
                selectElement.parentNode.insertBefore(customSelect, selectElement.nextSibling);
                selectElement.style.display = 'none';

                // Обработчики событий
                selectSelected.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeAllSelect(this);
                    this.nextSibling.classList.toggle('select-hide');
                    this.classList.toggle('select-arrow-active');
                });
            }

            // Функция для управления видимостью таблицы супруга
            function toggleSpouseTable(selectedOption) {
                const spouseTable = document.querySelector('.inputTable');
                const spouseTableRow = spouseTable.closest('.formRow');

                if (selectedOption === 'Женат/Замужем') {
                    // Плавное отображение
                    spouseTableRow.style.opacity = '0';
                    spouseTableRow.style.maxHeight = '0';
                    spouseTableRow.style.overflow = 'hidden';
                    spouseTableRow.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    spouseTableRow.style.display = 'block';

                    setTimeout(() => {
                        spouseTableRow.style.opacity = '1';
                        spouseTableRow.style.maxHeight = '500px'; // Достаточная высота для таблицы
                    }, 10);
                } else {
                    // Плавное скрытие
                    spouseTableRow.style.opacity = '0';
                    spouseTableRow.style.maxHeight = '0';
                    spouseTableRow.style.overflow = 'hidden';
                    spouseTableRow.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';

                    // После завершения анимации скрываем элемент
                    setTimeout(() => {
                        spouseTableRow.style.display = 'none';
                    }, 400);
                }
            }

            // Закрываем все открытые select
            function closeAllSelect(elmnt) {
                const items = document.getElementsByClassName('select-items');
                const selected = document.getElementsByClassName('select-selected');

                for (let i = 0; i < selected.length; i++) {
                    if (elmnt !== selected[i] && elmnt !== selected[i].nextSibling) {
                        selected[i].classList.remove('select-arrow-active');
                        if (items[i]) items[i].classList.add('select-hide');
                    }
                }
            }

            // Закрываем select при клике вне его
            document.addEventListener('click', closeAllSelect);

            // Варианты для select вакансий
            const vacancyOptions = [
                'Агент по недвижимости (риелтор)',
                'Помощник риелтора',
                'Менеджер по аренде недвижимости',
                'Специалист по работе с клиентами (CRM-менеджер)',
                'Маркетолог в риелторское агентство',
                'Оценщик недвижимости',
                'Юрист по недвижимости',
                'Руководитель отдела продаж',
                'Координатор сделок',
                'Развиватель бизнеса (бизнес-партнер)'
            ];

            // Варианты для select семейного положения
            const maritalStatusOptions = [
                'Не женат/Не замужем',
                'Женат/Замужем',
                'В разводе',
                'Вдовец/Вдова',
                'Гражданский брак'
            ];

            // Инициализируем кастомные select'ы
            createCustomSelect('phoneNumber', vacancyOptions);
            createCustomSelect('maritalStatusSelect', maritalStatusOptions);

            // Инициализация состояния таблицы супруга
            const maritalStatusSelect = document.getElementById('maritalStatusSelect');
            toggleSpouseTable(maritalStatusSelect.value);
        });
    </script>
@endsection
