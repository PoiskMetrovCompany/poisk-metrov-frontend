<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система регистрации</title>
    @vite(['resources/css/candidatesProfiles/index.css'])
    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://unpkg.com/imask"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
<div id="root"></div>

<script type="text/babel">
    <?php echo '@verbatim'; ?>
    const { useState, useEffect, useRef } = React;

    function Header() {
        return (
            <header>
                <div className="formRow justify-space-between w-80">
                    <div style={{display: 'flex', alignItems: 'center'}}>
                        <img id="nonTextImg" src="/img/ logo без текста.png" alt="Логотип компании Поиск Метров" />
                        <h5 id="city">Город: <span>Новосибирск</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                                <path d="M6 9L12 15L18 9" />
                            </svg>
                        </h5>
                    </div>
                    <div className="w-80" style={{display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '30px'}}>
                        <span className="active">Кандидаты</span>
                        <span>Настройки</span>
                    </div>
                    <div style={{display: 'flex', justifyContent: 'space-between', minWidth: '250px'}}>
                        <button id="notifBtn"><img src="/img/ring.png" alt="Уведомлений нет" /></button>
                        <button id="exitBtn">Выйти из ЛК <img src="/img/arowRight.png" alt="Стрелочка вправо" /></button>
                    </div>
                </div>
            </header>
        );
    }
    function ShowForm({ vacancyKey }) { 
        const [isSelectOpen, setIsSelectOpen] = useState(false);
        const [selectedOption, setSelectedOption] = useState({
            value: 'new',
            text: 'Новая анкета'
        });
        const [commentValue, setCommentValue] = useState('');
        const [isUpdating, setIsUpdating] = useState(false); 

        const selectOptions = [
            { value: 'new', text: 'Новая анкета' },
            { value: 'needs-work', text: 'Нужна доработка' },
            { value: 'checked', text: 'Проверен' },
            { value: 'rejected', text: 'Отклонен' }
        ];

        const getCsrfToken = () => {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
            return metaTag.getAttribute('content');
        }

        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'XSRF-TOKEN') {
                return decodeURIComponent(value);
            }
        }

        return 'Zva2RlvTSh5wTQogjJMfE8v5ObQoOSIcL40Xwc5d';
        };

        const mapStatusForAPI = (statusValue) => {
        const statusMap = {
            'new': 'Новая анкета',
            'needs-work': 'Нужна доработка', 
            'checked': 'Проверен',
            'rejected': 'Отклонен'
        };
        return statusMap[statusValue] || statusValue;
        };

        const getAccessToken = () => {
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'access_token') {
                return value;
            }
        }
        return null;
        };

        const updateCandidateStatus = async (newStatus) => {
        const accessToken = getAccessToken();

        if (!accessToken) {
            console.error('Access token не найден в cookies');
            return false;
        }

        if (!vacancyKey) {
            console.error('Ключ кандидата не передан в props');
            return false;
        }

        setIsUpdating(true);

        const mappedStatus = mapStatusForAPI(newStatus);
        const requestData = {
            key: vacancyKey,
            status: mappedStatus,
            comment: commentValue || ""
        };

        console.log('=== НАЧАЛО ЗАПРОСА ОБНОВЛЕНИЯ СТАТУСА ===');
        console.log('vacancyKey:', vacancyKey);
        console.log('newStatus (original):', newStatus);
        console.log('mappedStatus:', mappedStatus);
        console.log('commentValue:', commentValue);
        console.log('requestData:', requestData);

        try {
        const csrfToken = getCsrfToken();
        console.log('CSRF токен:', csrfToken);
        console.log('Access токен (первые 20 символов):', accessToken.substring(0, 20) + '...');

        const headers = {
            'accept': 'application/json',
            'Authorization': `Bearer ${accessToken}`,
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        };

        console.log('=== ДЕТАЛИ ЗАПРОСА ===');
        console.log('URL:', '/api/v1/candidates/update');
        console.log('Метод:', 'POST');
        console.log('Заголовки:', headers);
        console.log('Тело запроса (JSON):', JSON.stringify(requestData, null, 2));

        console.log('=== ОТПРАВКА ЗАПРОСА ===');

        const response = await fetch('/api/v1/candidates/update', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(requestData)
        });

        console.log('=== ОТВЕТ СЕРВЕРА ===');
        console.log('Статус ответа:', response.status);
        console.log('Статус текст:', response.statusText);

        if (response.ok) {
            const result = await response.json();
            console.log('✅ Статус успешно обновлен:', result);
            return true;
        } else {
            const errorText = await response.text();
            console.error('❌ Ошибка при обновлении статуса. Статус:', response.status);
            console.error('❌ Текст ошибки от сервера:', errorText);

        try {
            const errorJson = JSON.parse(errorText);
            console.error('❌ Ошибка (JSON):', errorJson);
        } catch (e) {
            console.error('❌ Ошибка - не JSON формат');
        }

        return false;
        }
        } catch (error) {
            console.error('=== ОШИБКА ЗАПРОСА ===');
            console.error('Тип ошибки:', error.name);
            console.error('Сообщение ошибки:', error.message);
            console.error('Полная ошибка:', error);

        return false;
        } finally {
            console.log('=== ЗАВЕРШЕНИЕ ЗАПРОСА ===');
            setIsUpdating(false);
        }
        };

        const handleSelectToggle = (e) => {
            e.stopPropagation();
            setIsSelectOpen(!isSelectOpen);
        };

        const handleOptionSelect = async (option) => {
            if (selectedOption.value !== option.value) {

            const success = await updateCandidateStatus(option.value);

            if (success) {

                setSelectedOption(option);
                console.log('Статус изменен на:', option.text);
            } else {
                console.error('Не удалось обновить статус');

            }
            }

            setIsSelectOpen(false);
        };

        const handleCommentChange = (e) => {
            setCommentValue(e.target.value);
        };

        const handleAddComment = async () => {
            if (!commentValue.trim()) {
                console.warn('Комментарий пустой, отправка не требуется');
            return;
        }

        const accessToken = getAccessToken();

        if (!accessToken) {
            console.error('Access token не найден в cookies');
            return;
        }

        if (!vacancyKey) {
            console.error('Ключ кандидата не передан в props');
            return;
        }

        const requestData = {
            key: vacancyKey,
            status: "", 
            comment: commentValue.trim()
        };

        console.log('=== НАЧАЛО ОТПРАВКИ КОММЕНТАРИЯ ===');
        console.log('Комментарий для отправки:', commentValue);
        console.log('requestData:', requestData);

        try {
            const csrfToken = getCsrfToken();
            console.log('CSRF токен:', csrfToken);

            const headers = {
                'accept': 'application/json',
                'Authorization': `Bearer ${accessToken}`,
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            };

            console.log('=== ДЕТАЛИ ЗАПРОСА КОММЕНТАРИЯ ===');
            console.log('URL:', '/api/v1/candidates/update');
            console.log('Метод:', 'POST');
            console.log('Заголовки:', headers);
            console.log('Тело запроса (JSON):', JSON.stringify(requestData, null, 2));

            const response = await fetch('/api/v1/candidates/update', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(requestData)
            });

            console.log('=== ОТВЕТ СЕРВЕРА НА КОММЕНТАРИЙ ===');
            console.log('Статус ответа:', response.status);

            if (response.ok) {
                const result = await response.json();
                console.log('✅ Комментарий успешно отправлен:', result);
                setCommentValue('');
            } else {
                const errorText = await response.text();
                console.error('❌ Ошибка при отправке комментария. Статус:', response.status);
                console.error('❌ Текст ошибки:', errorText);
            }
        } catch (error) {
            console.error('=== ОШИБКА ОТПРАВКИ КОММЕНТАРИЯ ===');
            console.error('Ошибка:', error);
        }
        };

        useEffect(() => {
            const handleClickOutside = (e) => {
                if (isSelectOpen && !e.target.closest('#customSelect')) {
                    setIsSelectOpen(false);
                }
            };

            const handleKeyDown = (e) => {
                if (e.key === 'Escape') {
                    setIsSelectOpen(false);
                }
            };

            document.addEventListener('click', handleClickOutside);
            document.addEventListener('keydown', handleKeyDown);

            return () => {
                document.removeEventListener('click', handleClickOutside);
                document.removeEventListener('keydown', handleKeyDown);
            };
        }, [isSelectOpen]);

        return (
        <>
        <header>
            <div className="formRow justify-space-between w-60">
                <div style={{display: 'flex', alignItems: 'center'}}>
                    <img id="nonTextImg" src="img/ logo без текста.png" alt="Логотип компании Поиск Метров" />
                    <h5 id="city">Город: <span>Новосибирск</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                            <path d="M6 9L12 15L18 9" />
                        </svg>
                    </h5>
                </div>
                <div className="w-60" style={{display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '30px'}}>
                    <span style={{cursor: 'pointer'}}>Кандидаты</span>
                    <span style={{cursor: 'pointer'}} className="active">Настройки</span>
                </div>
                <div style={{display: 'flex', justifyContent: 'space-between', minWidth: '250px'}}>
                    <button id="notifBtn"><img src="/img/ring.png" alt="Уведомлений нет" /></button>
                    <button id="exitBtn">Выйти из ЛК <img src="/img/arowRight.png" alt="Стрелочка вправо" /></button>
                </div>
            </div>
        </header>
        <main style={{marginTop: '5rem'}}>
            <section>
                <div className="center-card big">
                    <div className="fixedMenu">
                        <div className="navArea">
                            <div className={`yellowSelect ${isSelectOpen ? 'open' : ''} ${isUpdating ? 'updating' : ''}`} id="customSelect">
                            <div className="select-trigger" id="selectTrigger" onClick={handleSelectToggle} style={{opacity: isUpdating ? 0.6 : 1}}>
                                {selectedOption.text}
                                {isUpdating && <span style={{marginLeft: '10px'}}>...</span>}
                                <div className="trigger-icons"></div>
                            </div>
                            <div className="select-dropdown" id="selectDropdown">
                                {selectOptions.map((option) => (
                                <div
                                    key={option.value}
                                    className={`select-option ${selectedOption.value === option.value ? 'selected' : ''}`}
                                data-value={option.value}
                                onClick={() => handleOptionSelect(option)}
                                style={{opacity: isUpdating ? 0.6 : 1, pointerEvents: isUpdating ? 'none' : 'auto'}}
                                >
                                {option.text}
                            </div>
                            ))}
                        </div>
                    </div>
                    <a className="activeLink" href="#">Общие сведенья</a>
                    <a href="#">Паспотные данные</a>
                    <a href="#">Состав семьи</a>
                    <a href="#">Юридический статус</a>
                </div>
                <div className="navArea" style={{marginTop: '3rem'}}>
                                        <textarea
                                            name="comment"
                                            id="commentArea"
                                            placeholder="Написать комментарий"
                                            value={commentValue}
                                            onChange={handleCommentChange}
                                        ></textarea>
                    <button id="addComment" onClick={handleAddComment}>Оставить коментарий</button>
                </div>
                </div>
                <p style={{position: 'absolute', top: '-2.7rem', left: '0', display: 'flex', alignItems: 'center', gap: '1rem', cursor: 'pointer'}}>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.5 3L2 7.5L6.5 12M2.5 7.5H14" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                    </svg>
                    Вернуться к списку
                </p>

                <div className="formRow justify-space-between">
                    <h3 style={{width: 'auto', display: 'flex', alignItems: 'center'}}>Александров Александр Александрович, 26 лет</h3>
                    <p>Дата подачи</p>
                </div>
                <span id="line"></span>
                <div className="formRow justify-space-between">
                    <h4 style={{width: 'auto', display: 'flex', alignItems: 'center', marginTop: '0'}}>Агент по недвижимости</h4>
                    <p>10.07.2024, 15:47</p>
                </div>
                <div id="surnameChangeReason" className="toggle-block" style={{width: '100%'}}>
                    <div className="formRow">
                        <div className="input-container">
                            <label htmlFor="reasonOfChange" id="formLabel" className="formLabel">Причина изменения фамилии</label>
                            <input type="text" name="reasonOfChange" id="reasonOfChange" className="formInput big" placeholder="Опишите, почему поменяли фамилию" defaultValue="Замужество" readOnly />
                        </div>
                    </div>
                </div>
                <div className="formRow justify-space-between">
                    <div className="input-container w-49">
                        <label htmlFor="birthDate" id="formLabel" className="formLabel">Дата рождения</label>
                        <input style={{width: '100%'}} type="text" name="birthDate" id="birthDate" className="formInput" placeholder="01.01.1990" defaultValue="15.03.1995" readOnly />
                    </div>
                    <div className="input-container w-49">
                        <label htmlFor="birthPlace" id="formLabel" className="formLabel">Место рождения</label>
                        <input style={{width: '100%'}} type="text" name="birthPlace" id="birthPlace" className="formInput" placeholder="Страна и город" defaultValue="Российская Федерация, г. Москва" readOnly />
                    </div>
                </div>
                <div className="formRow justify-space-between">
                    <div className="input-container w-49">
                        <label htmlFor="mobileNumber" id="mobileNumber" className="formLabel">Мобильный телефон</label>
                        <input style={{width: '100%'}} type="tel" name="mobileNumber" id="mobileNumber" className="formInput" placeholder="+7(999)999-99-99" defaultValue="+7(905)123-45-67" readOnly />
                    </div>
                    <div className="input-container w-49">
                        <label htmlFor="domesticNumber" id="domesticNumber" className="formLabel">Домашний телефон</label>
                        <input style={{width: '100%'}} type="tel" name="domesticNumber" id="domesticNumber" className="formInput" placeholder="999 999" defaultValue="495 234-56-78" readOnly />
                    </div>
                </div>
                <div className="formRow justify-space-between">
                    <div className="input-container w-49">
                        <label htmlFor="email" id="email" className="formLabel">E-mail</label>
                        <input style={{width: '100%'}} type="email" name="email" id="email" className="formInput" defaultValue="rabotnik@gmail.com" placeholder="example@gmail.com" readOnly />
                    </div>
                    <div className="input-container w-49">
                        <label htmlFor="INN" id="INN" className="formLabel">ИНН</label>
                        <input style={{width: '100%'}} type="number" name="INN" id="INN" className="formInput" defaultValue="324716976901" placeholder="123456789012" readOnly />
                    </div>
                </div>

                <div className="formRow" style={{marginTop: '50px'}}>
                    <h3>Паспортные данные</h3>
                </div>
                
                <div className="formRow justify-space-between">
                    <div className="input-container w-49">
                        <label htmlFor="passwordSeriaNumber" id="passwordSeriaNumber" className="formLabel">Серия и номер </label>
                        <input style={{width: '100%'}} type="tel" name="passwordSeriaNumber" id="passwordSeriaNumber" className="formInput" placeholder="1234 567890" defaultValue="4514 123456" readOnly />
                    </div>
                    <div className="input-container w-49">
                        <label htmlFor="dateOfIssue" id="dateOfIssue" className="formLabel">Дата выдачи</label>
                        <input style={{width: '100%'}} type="tel" name="dateOfIssue" id="dateOfIssue" className="formInput" placeholder="01.01.1990" defaultValue="20.03.2015" readOnly />
                    </div>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="issuedBy" id="issuedBy" className="formLabel">Кем выдан</label>
                        <input style={{width: '100%'}} type="text" name="issuedBy" id="issuedBy" className="formInput" placeholder="ОФУМС России" defaultValue="УФМС России по г. Москве" readOnly />
                    </div>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="adressOfPermanentReg" id="adressOfPermanentReg" className="formLabel">Адрес постоянной регистрации</label>
                        <input style={{width: '100%'}} type="text" name="adressOfPermanentReg" id="adressOfPermanentReg" className="formInput" placeholder="Адрес постоянной регистрации" defaultValue="г. Москва, ул. Тверская, д. 15, кв. 42" readOnly />
                    </div>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="adressOfTemporaryReg" id="adressOfTemporaryReg" className="formLabel">Адрес временной регистрации</label>
                        <input style={{width: '100%'}} type="text" name="adressOfTemporaryReg" id="adressOfTemporaryReg" className="formInput" placeholder="Адрес временной регистрации" defaultValue="г. Санкт-Петербург, пр. Невский, д. 28, кв. 15" readOnly />
                    </div>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="adressOfFactialLiving" id="adressOfFactialLiving" className="formLabel">Адрес фактического проживания</label>
                        <input style={{width: '100%'}} type="text" name="adressOfFactialLiving" id="adressOfFactialLiving" className="formInput" placeholder="Адрес фактического проживания" defaultValue="г. Санкт-Петербург, пр. Невский, д. 28, кв. 15" readOnly />
                    </div>
                </div>
                <div className="formRow" style={{marginTop: '50px'}}>
                    <h3>Состав семьи</h3>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="maritalStatus" id="maritalStatus" className="formLabel">Семейное положение</label>
                        <input style={{width: '100%'}} type="text" name="maritalStatus" id="maritalStatus" className="formInput" placeholder="Адрес фактического проживания" defaultValue="Состоит в зарегистрированном браке" readOnly />
                    </div>
                </div>
                <div className="formRow">
                    <table className="inputTable showTable">
                        <tbody>
                        <tr>
                            <td colSpan="2">
                                <input type="text" name="FIOSuprug" placeholder="ФИО супруга(-и)" defaultValue="Иванов Сергей Петрович" readOnly />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="dateOfBirthTable" placeholder="Дата рождения" defaultValue="12.07.1993" readOnly />
                            </td>
                            <td>
                                <input type="tel" name="phoneNumberTable" placeholder="Телефон" defaultValue="+7(906)987-65-43" readOnly />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="placeOfStudy" placeholder="Место учебы/работы, рабочий телефон" defaultValue="ООО «Стройком», +7(495)123-45-67" readOnly />
                            </td>
                            <td>
                                <input type="text" name="placeOfLiving" placeholder="Место проживания" defaultValue="г. Санкт-Петербург, пр. Невский, д. 28, кв. 15" readOnly />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div className="formRow flex-direction-column">
                    <h3>Данные совершеннолетнего ребенка</h3>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="hasGraduatedChild" id="hasGraduatedChild" className="formLabel">Наличие совершеннолетних детей</label>
                        <input style={{width: '100%'}} type="text" name="hasGraduatedChild" id="hasGraduatedChild" className="formInput" placeholder="Адрес фактического проживания" defaultValue="Есть" readOnly />
                    </div>
                </div>
                <div id="doesHaveAdultChildren" className="toggle-block" style={{width: '100%'}}>
                    <div className="formRow showTable">
                        <table className="inputTable showTable">
                            <tbody>
                            <tr>
                                <td colSpan="2">
                                    <input type="text" name="FIOChildren1" placeholder="ФИО ребенка" defaultValue="Иванова Анна Сергеевна" readOnly />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="dateOfBirthChildren1" placeholder="Дата рождения" defaultValue="05.04.2005" readOnly />
                                </td>
                                <td>
                                    <input type="tel" name="phoneNumberChildren1" placeholder="Телефон" defaultValue="+7(925)456-78-90" readOnly />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="placeOfStudyChildren1" placeholder="Место учебы/работы, рабочий телефон" defaultValue="МГУ, факультет психологии" readOnly />
                                </td>
                                <td>
                                    <input type="text" name="placeOfLivingChildren1" placeholder="Место проживания" defaultValue="г. Москва, общежитие МГУ" readOnly />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="formRow flex-direction-column">
                    <h3>2. Члены семьи старше 18 лет</h3>
                </div>

                <div id="doesHaveAdultRelative" className="toggle-block" style={{width: '100%'}}>
                    <div className="formRow">
                        <table className="inputTable showTable">
                            <tbody>
                            <tr>
                                <td colSpan="2">
                                    <input type="text" name="FIORelative1" placeholder="Степень родства, ФИО члена семьи" defaultValue="Мать, Петрова Елена Владимировна" readOnly />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="dateOfBirthRelative1" placeholder="Дата рождения" defaultValue="23.09.1970" readOnly />
                                </td>
                                <td>
                                    <input type="tel" name="phoneNumberRelative1" placeholder="Телефон" defaultValue="+7(916)234-56-78" readOnly />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="placeOfStudyRelative1" placeholder="Место учебы/работы, рабочий телефон" defaultValue="Пенсионер" readOnly />
                                </td>
                                <td>
                                    <input type="text" name="placeOfLivingRelative1" placeholder="Место проживания" defaultValue="г. Москва, ул. Тверская, д. 15, кв. 42" readOnly />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="formRow flex-direction-column" style={{marginTop: '50px'}}>
                    <h3>Юридический статус</h3>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="militaryDuty" id="militaryDuty" className="formLabel">Статус военнообязанного</label>
                        <input type="text" name="militaryDuty" id="militaryDuty" className="formInput big" defaultValue="Является военнообязанным" readOnly />
                    </div>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="reasonOfChange" id="formLabel" className="formLabel">Наличие уголовной или административной ответсвенности</label>
                        <input type="text" name="reasonOfChange" id="reasonOfChange" className="formInput big" defaultValue="Да, имеется" readOnly />
                    </div>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="whyPrisoner" id="whyPrisoner" className="formLabel">Причины привлечения к уголовной или административной ответственности</label>
                        <input type="text" name="whyPrisoner" id="whyPrisoner" className="formInput big" defaultValue="Захотелось" readOnly />
                    </div>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="isLegalEntity" id="isLegalEntity" className="formLabel">Является или нет (со-)учредителем юридического лица</label>
                        <input type="text" name="isLegalEntity" id="isLegalEntity" className="formInput big" defaultValue="Да, является" readOnly />
                    </div>
                </div>
                <div className="formRow">
                    <div className="input-container">
                        <label htmlFor="LegalEntityActivity" id="LegalEntityActivity" className="formLabel">Является или нет (со-)учредителем юридического лица</label>
                        <input type="text" name="LegalEntityActivity" id="isLegalEntity" className="formInput big" defaultValue="Готовим шаурму" readOnly />
                    </div>
                </div>
                </div>
            </section>
        </main>
        </>
        );
    }
    function App() {
        const [isCalendarOpen, setIsCalendarOpen] = useState(false);
        const [selectedVacancyKey, setSelectedVacancyKey] = useState(null); // Состояние для выбранного кандидата
        const filtersButtonRef = useRef(null);

        const handleFiltersClick = () => {
            setIsCalendarOpen(true);
        };

        const handleCalendarClose = () => {
            setIsCalendarOpen(false);
        };

        // Обработчик клика по строке кандидата
        const handleRowClick = (vacancyKey) => {
            setSelectedVacancyKey(vacancyKey);
        };

        // Обработчик возврата к списку кандидатов
        const handleBackToList = () => {
            setSelectedVacancyKey(null);
        };

        return (
            <>
                {/* Показываем Header только когда не открыта форма кандидата */}
                {!selectedVacancyKey && <Header />}

                <main>
                    {selectedVacancyKey ? (
                        // Показываем форму кандидата, если выбран кандидат
                        <ShowForm
                            vacancyKey={selectedVacancyKey}
                            onBackToList={handleBackToList}
                        />
                    ) : (
                        // Показываем таблицу кандидатов, если кандидат не выбран
                        <>
                            <CandidatesTable
                                onFiltersClick={handleFiltersClick}
                                onRowClick={handleRowClick}
                                filtersButtonRef={filtersButtonRef}
                            />
                            <FiltersCalendar
                                isOpen={isCalendarOpen}
                                onClose={handleCalendarClose}
                                filtersButtonRef={filtersButtonRef}
                            />
                        </>
                    )}
                </main>
            </>
        );
    }

    // Монтируем главное приложение
    ReactDOM.render(React.createElement(App), document.getElementById('root'));
    <?php echo '@endverbatim'; ?>
</script>
</body>
</html>

