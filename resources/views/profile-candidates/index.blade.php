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

        // !!!!!!!!!!!!!-----Форма регистрации кандидата-----!!!!!!!!!!!!!!!!!!!!




        // Компонент регистрации кандидата
        function CandidateRegForm() {
            const [isCodeMode, setIsCodeMode] = useState(false);
            const [isPhoneValidated, setIsPhoneValidated] = useState(false);
            const [phoneValue, setPhoneValue] = useState('');
            const [isCheckboxChecked, setIsCheckboxChecked] = useState(false);
            const [showCheckmark, setShowCheckmark] = useState(false);
            const [isLoading, setIsLoading] = useState(false);
            const [isAuthLoading, setIsAuthLoading] = useState(false);
            const [error, setError] = useState('');
            const [userAttributes, setUserAttributes] = useState(null);
            const [isAuthenticated, setIsAuthenticated] = useState(false);
            const [authResult, setAuthResult] = useState(null);
            
            const phoneInputRef = useRef(null);
            const currentMaskRef = useRef(null);

            useEffect(() => {
                if (phoneInputRef.current && !isCodeMode) {
                    const maskOptions = {
                        mask: '+{7}(000) 000-00-00'
                    };
                    currentMaskRef.current = IMask(phoneInputRef.current, maskOptions);
                }

                return () => {
                    if (currentMaskRef.current) {
                        currentMaskRef.current.destroy();
                    }
                };
            }, [isCodeMode]);

            useEffect(() => {
                if (phoneInputRef.current && isCodeMode) {
                    if (currentMaskRef.current) {
                        currentMaskRef.current.destroy();
                    }
                    const maskOptions = {
                        mask: ' 0 0 0 0 0 0 ',
                        lazy: false,
                        placeholderChar: " _ "
                    };
                    currentMaskRef.current = IMask(phoneInputRef.current, maskOptions);
                    phoneInputRef.current.focus();
                }
            }, [isCodeMode]);

            const checkButtonState = () => {
                if (!isCodeMode) {
                    const isPhoneValid = phoneValue.length >= 17;
                    return isPhoneValid && isCheckboxChecked && !isLoading;
                }
                return false;
            };

            const checkCode = (value) => {
                const enteredCode = value.replace(/\s/g, '').replace(/_/g, '');
                if (enteredCode.length === 6) {
                    setShowCheckmark(true);
                    console.log('Код введен полностью!');
                } else {
                    setShowCheckmark(false);
                }
            };

            const handleInputChange = (e) => {
                const value = e.target.value;
                console.log('handleInputChange вызван с значением:', value);
                console.log('isCodeMode:', isCodeMode);
                setPhoneValue(value);
                
                // Очищаем ошибку при изменении значения
                if (error) {
                    setError('');
                }
                
                if (isCodeMode) {
                    checkCode(value);
                }
            };

            const handleCheckboxChange = (e) => {
                setIsCheckboxChecked(e.target.checked);
                // Очищаем ошибку при изменении чекбокса
                if (error) {
                    setError('');
                }
            };

            // Функция для отправки запроса на получение кода
            const sendCodeRequest = async (phone) => {
                try {
                    setIsLoading(true);
                    setError('');
                    
                    // Отправляем запрос на сервер
                    const response = await axios.post('/api/v1/account/set-code', {
                        phone: phone
                    }, {
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    // Проверяем успешность запроса
                    if (response.data.request) {
                        setUserAttributes(response.data.attributes);
                        console.log('Код отправлен успешно:', response.data);
                        return true;
                    } else {
                        setError('Ошибка при отправке кода');
                        return false;
                    }
                } catch (error) {
                    console.error('Ошибка при отправке запроса:', error);
                    
                    if (error.response) {
                        // Сервер ответил с кодом ошибки
                        if (error.response.status === 404) {
                            setError('Пользователь не найден');
                        } else {
                            setError(error.response.data?.error || 'Ошибка сервера');
                        }
                    } else if (error.request) {
                        // Запрос был отправлен, но ответа не получено
                        setError('Ошибка соединения с сервером');
                    } else {
                        // Ошибка при настройке запроса
                        setError('Ошибка при отправке запроса');
                    }
                    return false;
                } finally {
                    setIsLoading(false);
                }
            };

            // Функция для отправки запроса на аутентификацию
            const sendAuthRequest = async () => {
                console.log('sendAuthRequest вызвана');
                try {
                    setIsAuthLoading(true);
                    setError('');
                    
                    // Получаем введенный код из значения инпута или маски
                    let enteredCode = phoneValue;
                    
                    // Если используется маска, попробуем получить значение из нее
                    if (currentMaskRef.current && currentMaskRef.current.unmaskedValue) {
                        enteredCode = currentMaskRef.current.unmaskedValue;
                        console.log('Код из маски:', enteredCode);
                    } else {
                        // Убираем пробелы и подчеркивания из обычного значения
                        enteredCode = phoneValue.replace(/\s/g, '').replace(/_/g, '');
                        console.log('Код из phoneValue:', enteredCode);
                    }
                    
                    console.log('Финальный введенный код:', enteredCode);
                    
                    // Если код не введен, показываем ошибку
                    if (enteredCode.length === 0) {
                        console.log('Код не введен');
                        setError('Введите код из СМС');
                        return false;
                    }

                    // Получаем номер телефона из userAttributes
                    const phoneToAuth = userAttributes?.phone;
                    console.log('Номер для аутентификации:', phoneToAuth);
                    if (!phoneToAuth) {
                        console.log('Номер телефона не найден');
                        setError('Ошибка: номер телефона не найден');
                        return false;
                    }

                    console.log('Отправляем запрос на аутентификацию...');
                    // Отправляем запрос на аутентификацию
                    const response = await axios.post('/api/v1/account/auth', {
                        phone: phoneToAuth,
                        code: enteredCode
                    }, {
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    console.log('Ответ сервера:', response.data);
                    // Проверяем успешность запроса
                    if (response.data.request && response.data.attributes) {
                        setAuthResult(response.data.attributes);
                        setIsAuthenticated(true);
                        console.log('Аутентификация успешна:', response.data);
                        
                        // Сохраняем токен в cookie
                        if (response.data.attributes.access_token) {
                            // Устанавливаем cookie с токеном на 30 дней
                            const expirationDate = new Date();
                            expirationDate.setTime(expirationDate.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30 дней
                            document.cookie = `access_token=${response.data.attributes.access_token}; expires=${expirationDate.toUTCString()}; path=/; SameSite=Strict`;
                            console.log('Токен сохранен в cookie:', response.data.attributes.access_token);
                        }
                        
                        return true;
                    } else {
                        console.log('Ошибка в ответе сервера');
                        setError('Ошибка при аутентификации');
                        return false;
                    }
                } catch (error) {
                    console.error('Ошибка при аутентификации:', error);
                    
                    if (error.response) {
                        // Сервер ответил с кодом ошибки
                        console.log('Ошибка ответа сервера:', error.response.status, error.response.data);
                        if (error.response.status === 401) {
                            setError('Неверный код');
                        } else if (error.response.status === 404) {
                            setError('Пользователь не найден');
                        } else {
                            setError(error.response.data?.error || 'Ошибка сервера');
                        }
                    } else if (error.request) {
                        // Запрос был отправлен, но ответа не получено
                        console.log('Ошибка запроса:', error.request);
                        setError('Ошибка соединения с сервером');
                    } else {
                        // Ошибка при настройке запроса
                        console.log('Ошибка настройки:', error.message);
                        setError('Ошибка при отправке запроса');
                    }
                    return false;
                } finally {
                    console.log('Завершение sendAuthRequest');
                    setIsAuthLoading(false);
                }
            };

            const handleGetCodeClick = async (e) => {
                e.preventDefault();
                
                if (!isCodeMode) {
                    // Первичная отправка кода
                    const success = await sendCodeRequest(phoneValue);
                    
                    if (success) {
                        setIsCodeMode(true);
                        setPhoneValue('');
                        setShowCheckmark(false);
                    }
                } else {
                    // Повторная отправка кода
                    // Получаем сохраненный номер телефона из userAttributes
                    const phoneToResend = userAttributes?.phone || phoneValue;
                    const success = await sendCodeRequest(phoneToResend);
                    
                    if (success) {
                        setPhoneValue('');
                        setShowCheckmark(false);
                    }
                }
            };

            const handleSendCodeClick = async (e) => {
                e.preventDefault();
                console.log('Кнопка "Отправить код" нажата');
                console.log('Текущее значение phoneValue:', phoneValue);
                console.log('userAttributes:', userAttributes);
                await sendAuthRequest();
            };

            const handleChangeNumber = (e) => {
                e.preventDefault();
                
                setIsCodeMode(false);
                setPhoneValue('');
                setShowCheckmark(false);
                setError('');
                setUserAttributes(null);
                setIsAuthenticated(false);
                setAuthResult(null);
                
                setTimeout(() => {
                    if (phoneInputRef.current) {
                        phoneInputRef.current.focus();
                    }
                }, 0);
            };

            const getButtonText = () => {
                if (isLoading) {
                    return "Отправка...";
                }
                return isCodeMode ? "Получить код повторно" : "Получить код";
            };

            const getButtonClass = () => {
                if (isLoading) {
                    return "formBtn btn-inactive";
                }
                if (!isCodeMode) {
                    return checkButtonState() ? "formBtn btn-active" : "formBtn btn-inactive";
                }
                return "formBtn btn-active";
            };

            const isButtonDisabled = () => {
                if (isLoading) return true;
                if (!isCodeMode) return !checkButtonState();
                return false;
            };

            const getSendCodeButtonClass = () => {
                if (isAuthLoading) {
                    return "formBtn btn-inactive";
                }
                return "formBtn btn-active";
            };

            const isSendCodeButtonDisabled = () => {
                return isAuthLoading;
            };

            // Если пользователь аутентифицирован, показываем результат
            if (isAuthenticated && authResult) {
                return (
                    <>
                        <header>
                            <img src="img/Logo с текстом.png" alt="Картинка с логотипом агенства и подписью Поиск метров" />
                        </header>

                        <main>
                            <section>
                                <div className="center-card">
                                    <h1>Аутентификация успешна!</h1>
                                    <p>Добро пожаловать в систему</p>
                                    
                                    <div>
                                        <strong>Информация о пользователе:</strong><br />
                                        ID: {authResult.user.id}<br />
                                        Роль: {authResult.user.role}<br />
                                        Телефон: {authResult.user.phone}<br />
                                        Токен: {authResult.access_token.substring(0, 20)}...
                                    </div>
                                    
                                    <button 
                                        className="formBtn btn-active"
                                        onClick={handleChangeNumber}
                                        style={ {marginTop: '20px'} }
                                    >
                                        Выйти
                                    </button>
                                </div>
                            </section>
                        </main>
                    </>
                );
            }

            return (
                <>
                    <header>
                        <img src="img/Logo с текстом.png" alt="Картинка с логотипом агенства и подписью Поиск метров" />
                    </header>

                    <main>
                        <section>
                            <div className="center-card">
                                <h1>Регистрация кандидата</h1>
                                <p>Введите номер телефона, чтобы авторизоваться в системе и получить доступ к анкете кандидата</p>

                                <form action="#">
                                    <div className="input-container">
                                        <label htmlFor="phoneNumber" id="formLabel" className="formLabel">
                                            {isCodeMode ? "Код из СМС" : "Телефон"}
                                        </label>
                                        <input 
                                            type="tel" 
                                            name="phoneNumber" 
                                            id="phoneNumber" 
                                            className="formInput" 
                                            placeholder={isCodeMode ? "Введите код из СМС" : "Введите номер"}
                                            value={phoneValue}
                                            onChange={handleInputChange}
                                            ref={phoneInputRef}
                                            disabled={isLoading || isAuthLoading}
                                        />
                                        {showCheckmark && (
                                            <div className="checkmark-icon" id="checkmarkIcon">
                                                <svg viewBox="0 0 24 24">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                </svg>
                                            </div>
                                        )}
                                    </div>

                                    {/* Отображение ошибки */}
                                    {error && (
                                        <div className="error-message">
                                            {error}
                                        </div>
                                    )}

                                    <button 
                                        id="getCodeBtn" 
                                        className={getButtonClass()}
                                        disabled={isButtonDisabled()}
                                        onClick={handleGetCodeClick}
                                    >
                                        {getButtonText()}
                                    </button><br />
                                    
                                    {/* Кнопка отправки кода - показывается только в режиме ввода кода */}
                                    {isCodeMode && (
                                        <button 
                                            id="sendCodeBtn" 
                                            className={getSendCodeButtonClass()}
                                            disabled={isSendCodeButtonDisabled()}
                                            onClick={handleSendCodeClick}
                                            style={ {marginTop: '10px'} }
                                        >
                                            {isAuthLoading ? "Отправка..." : "Отправить код"}
                                        </button>
                                    )}
                                    
                                    <div 
                                        className="checkboxRow" 
                                        id="checkboxRow"
                                        style={ {display: isCodeMode ? 'none' : 'flex'} }
                                    >
                                        <label className="custom-checkbox" htmlFor="personalData">
                                            <input 
                                                type="checkbox" 
                                                name="personalData" 
                                                id="personalData"
                                                checked={isCheckboxChecked}
                                                onChange={handleCheckboxChange}
                                                disabled={isLoading || isAuthLoading}
                                            />
                                            <span className="checkmark"></span>
                                        </label>
                                        <label htmlFor="personalData">
                                            Я даю согласие на обработку <span>своих персональных данных</span>
                                        </label>
                                    </div>
                                </form>
                                
                                {isCodeMode && (
                                    <a 
                                        href="#" 
                                        id="changeNumber"
                                        onClick={handleChangeNumber}
                                    >
                                        Изменить номер
                                    </a>
                                )}

                                {/* Отладочная информация (можно убрать в продакшене) */}
                                {userAttributes && !isAuthenticated && (
                                    <div style={ {marginTop: '20px', fontSize: '12px', color: '#666'} }>
                                        <strong>Отладка (получение кода):</strong><br />
                                        ID: {userAttributes.id}<br />
                                        Роль: {userAttributes.role}<br />
                                        Телефон: {userAttributes.phone}
                                    </div>
                                )}
                            </div>
                        </section>
                    </main>
                </>
            );
        }





// ===============================!!!!!!!!!Отправка формы кандидата!!!!!!!!!!!=================================


const SpouseTable = ({ formData, setFormData, isVisible }) => {
    const handleInputChange = (name, value) => {
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    if (!isVisible) return null;

    return (
        <div className="formRow" style={{
            opacity: 1,
            maxHeight: '500px',
            overflow: 'hidden',
            transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
        }}>
            <table className="inputTable">
                <caption className="tableLabel">
                    Данные супруга(-и)
                </caption>
                <tr>
                    <td colSpan="2">
                        <input 
                            type="text" 
                            name="FIOSuprug" 
                            placeholder="ФИО супруга(-и)" 
                            value={formData.FIOSuprug || ''}
                            onChange={(e) => handleInputChange('FIOSuprug', e.target.value)}
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input 
                            type="text" 
                            name="dateOfBirthTable" 
                            placeholder="Дата рождения" 
                            value={formData.dateOfBirthTable || ''}
                            onChange={(e) => handleInputChange('dateOfBirthTable', e.target.value)}
                        />
                    </td>
                    <td>
                        <input 
                            type="tel" 
                            name="phoneNumberTable" 
                            placeholder="Телефон" 
                            value={formData.phoneNumberTable || ''}
                            onChange={(e) => handleInputChange('phoneNumberTable', e.target.value)}
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input 
                            type="text" 
                            name="placeOfStudy" 
                            placeholder="Место учебы/работы, рабочий телефон" 
                            value={formData.placeOfStudy || ''}
                            onChange={(e) => handleInputChange('placeOfStudy', e.target.value)}
                        />
                    </td>
                    <td>
                        <input 
                            type="text" 
                            name="placeOfLiving" 
                            placeholder="Место проживания" 
                            value={formData.placeOfLiving || ''}
                            onChange={(e) => handleInputChange('placeOfLiving', e.target.value)}
                        />
                    </td>
                </tr>
            </table>
        </div>
    );
};

// Компонент таблицы члена семьи
const RelativeTable = ({ index, formData, setFormData }) => {
    const handleInputChange = (name, value) => {
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    return (
        <div className="formRow table-container" style={{
            opacity: 1,
            transform: 'translateY(0)',
            maxHeight: '216px',
            transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
        }}>
            <table className="inputTable">
                <caption className="tableLabel">Данные члена семьи</caption>
                <tr>
                    <td colSpan="2">
                        <input 
                            type="text" 
                            name={`FIORelative${index}`} 
                            placeholder="Степень родства, ФИО члена семьи" 
                            value={formData[`FIORelative${index}`] || ''}
                            onChange={(e) => handleInputChange(`FIORelative${index}`, e.target.value)}
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input 
                            type="text" 
                            name={`dateOfBirthRelative${index}`} 
                            placeholder="Дата рождения" 
                            value={formData[`dateOfBirthRelative${index}`] || ''}
                            onChange={(e) => handleInputChange(`dateOfBirthRelative${index}`, e.target.value)}
                        />
                    </td>
                    <td>
                        <input 
                            type="tel" 
                            name={`phoneNumberRelative${index}`} 
                            placeholder="Телефон" 
                            value={formData[`phoneNumberRelative${index}`] || ''}
                            onChange={(e) => handleInputChange(`phoneNumberRelative${index}`, e.target.value)}
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input 
                            type="text" 
                            name={`placeOfStudyRelative${index}`} 
                            placeholder="Место учебы/работы, рабочий телефон" 
                            value={formData[`placeOfStudyRelative${index}`] || ''}
                            onChange={(e) => handleInputChange(`placeOfStudyRelative${index}`, e.target.value)}
                        />
                    </td>
                    <td>
                        <input 
                            type="text" 
                            name={`placeOfLivingRelative${index}`} 
                            placeholder="Место проживания" 
                            value={formData[`placeOfLivingRelative${index}`] || ''}
                            onChange={(e) => handleInputChange(`placeOfLivingRelative${index}`, e.target.value)}
                        />
                    </td>
                </tr>
            </table>
        </div>
    );
};

// Компонент таблицы ребенка
const ChildrenTable = ({ index, formData, setFormData }) => {
    const handleInputChange = (name, value) => {
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    return (
        <div className="formRow table-container" style={{
            opacity: 1,
            transform: 'translateY(0)',
            maxHeight: '216px',
            transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
        }}>
            <table className="inputTable">
                <caption className="tableLabel">Данные совершеннолетнего ребенка</caption>
                <tr>
                    <td colSpan="2">
                        <input 
                            type="text" 
                            name={`FIOChildren${index}`} 
                            placeholder="ФИО ребенка" 
                            value={formData[`FIOChildren${index}`] || ''}
                            onChange={(e) => handleInputChange(`FIOChildren${index}`, e.target.value)}
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input 
                            type="text" 
                            name={`dateOfBirthChildren${index}`} 
                            placeholder="Дата рождения" 
                            value={formData[`dateOfBirthChildren${index}`] || ''}
                            onChange={(e) => handleInputChange(`dateOfBirthChildren${index}`, e.target.value)}
                        />
                    </td>
                    <td>
                        <input 
                            type="tel" 
                            name={`phoneNumberChildren${index}`} 
                            placeholder="Телефон" 
                            value={formData[`phoneNumberChildren${index}`] || ''}
                            onChange={(e) => handleInputChange(`phoneNumberChildren${index}`, e.target.value)}
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input 
                            type="text" 
                            name={`placeOfStudyChildren${index}`} 
                            placeholder="Место учебы/работы, рабочий телефон" 
                            value={formData[`placeOfStudyChildren${index}`] || ''}
                            onChange={(e) => handleInputChange(`placeOfStudyChildren${index}`, e.target.value)}
                        />
                    </td>
                    <td>
                        <input 
                            type="text" 
                            name={`placeOfLivingChildren${index}`} 
                            placeholder="Место проживания" 
                            value={formData[`placeOfLivingChildren${index}`] || ''}
                            onChange={(e) => handleInputChange(`placeOfLivingChildren${index}`, e.target.value)}
                        />
                    </td>
                </tr>
            </table>
        </div>
    );
};

// Основной компонент формы
function CandidateForm() {
    // Состояния для управления видимостью блоков
    const [surnameChanged, setSurnameChanged] = useState(true);
    const [haveChildren, setHaveChildren] = useState(true);
    const [haveFamilyMembers, setHaveFamilyMembers] = useState(true);
    const [criminalResponsibility, setCriminalResponsibility] = useState(false);
    const [legalEntity, setLegalEntity] = useState(false);
    const [militaryDuty, setMilitaryDuty] = useState(true);
    const [personalDataChecked, setPersonalDataChecked] = useState(false);

    const [selectedVacancy, setSelectedVacancy] = useState('');
    const [selectedMaritalStatus, setSelectedMaritalStatus] = useState('');
    const [showVacancyOptions, setShowVacancyOptions] = useState(false);
    const [showMaritalOptions, setShowMaritalOptions] = useState(false);

    const [relativeCounter, setRelativeCounter] = useState(1);
    const [childrenCounter, setChildrenCounter] = useState(1);

    // Массивы для хранения дополнительных таблиц
    const [additionalRelativeTables, setAdditionalRelativeTables] = useState([]);
    const [additionalChildrenTables, setAdditionalChildrenTables] = useState([]);

    // Новые состояния для API данных
    const [vacancyOptions, setVacancyOptions] = useState([]);
    const [isLoadingVacancies, setIsLoadingVacancies] = useState(true);
    const [vacancyError, setVacancyError] = useState('');

    // Состояния для семейного положения из API
    const [maritalStatusApiOptions, setMaritalStatusApiOptions] = useState([]);
    const [isLoadingMaritalStatuses, setIsLoadingMaritalStatuses] = useState(true);
    const [maritalStatusError, setMaritalStatusError] = useState('');

    // Состояния для отправки формы
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [submitError, setSubmitError] = useState('');
    const [submitSuccess, setSubmitSuccess] = useState(false);

    // Централизованное состояние для данных формы
    const [formData, setFormData] = useState({});

    // Хранение ключей для API запросов
    const [vacancyKey, setVacancyKey] = useState('');
    const [maritalStatusKey, setMaritalStatusKey] = useState('');

    // Функция для обновления данных формы
    const handleFormDataChange = (name, value) => {
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    // Функция для генерации случайных данных
    const generateRandomData = () => {
        const names = ['Иванов Иван Иванович', 'Петрова Мария Сергеевна', 'Сидоров Алексей Николаевич', 'Козлова Анна Владимировна', 'Морозов Дмитрий Александрович'];
        const cities = ['Москва', 'Санкт-Петербург', 'Новосибирск', 'Екатеринбург', 'Казань'];
        const countries = ['Россия', 'Казахстан', 'Беларусь', 'Украина'];
        const emails = ['example@gmail.com', 'test@yandex.ru', 'user@mail.ru', 'demo@example.com'];
        const reasons = ['Замужество', 'Развод', 'Личные причины', 'По семейным обстоятельствам'];
        
        const getRandomItem = (arr) => arr[Math.floor(Math.random() * arr.length)];
        const getRandomDate = () => {
            const start = new Date(1970, 0, 1);
            const end = new Date(2005, 11, 31);
            const date = new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
            return `${date.getDate().toString().padStart(2, '0')}.${(date.getMonth() + 1).toString().padStart(2, '0')}.${date.getFullYear()}`;
        };
        const getRandomPhone = () => `+7(${Math.floor(Math.random() * 900 + 100)})${Math.floor(Math.random() * 900 + 100)}-${Math.floor(Math.random() * 90 + 10)}-${Math.floor(Math.random() * 90 + 10)}`;
        const getRandomINN = () => Math.floor(Math.random() * 900000000000 + 100000000000).toString();
        const getRandomPassport = () => `${Math.floor(Math.random() * 9000 + 1000)} ${Math.floor(Math.random() * 900000 + 100000)}`;

        // Случайно выбираем вакансию и семейное положение
        if (vacancyOptions.length > 0) {
            setSelectedVacancy(getRandomItem(vacancyOptions));
        }
        
        const maritalOptions = maritalStatusApiOptions.length > 0 ? maritalStatusApiOptions : [
            'Не женат/Не замужем',
            'Женат/Замужем',
            'В разводе',
            'Вдовец/Вдова',
            'Гражданский брак'
        ];
        setSelectedMaritalStatus(getRandomItem(maritalOptions));

        // Заполняем основные поля
        const randomData = {
            FIO: getRandomItem(names),
            reasonOfChange: getRandomItem(reasons),
            birthDate: getRandomDate(),
            birthPlace: `${getRandomItem(countries)}, ${getRandomItem(cities)}`,
            mobileNumber: getRandomPhone(),
            domesticNumber: `${Math.floor(Math.random() * 900 + 100)} ${Math.floor(Math.random() * 900 + 100)}`,
            email: getRandomItem(emails),
            INN: getRandomINN(),
            passwordSeriaNumber: getRandomPassport(),
            dateOfIssue: getRandomDate(),
            issuedBy: 'ОФУМС России по городу Москве',
            adressOfPermanentReg: `г. ${getRandomItem(cities)}, ул. Ленина, д. ${Math.floor(Math.random() * 100 + 1)}`,
            adressOfTemporaryReg: `г. ${getRandomItem(cities)}, ул. Советская, д. ${Math.floor(Math.random() * 100 + 1)}`,
            adressOfFactialLiving: `г. ${getRandomItem(cities)}, ул. Пушкина, д. ${Math.floor(Math.random() * 100 + 1)}`,
            FIOSuprug: getRandomItem(names),
            whyPrisoner: 'Административное нарушение',
            LegalEntity: 'ООО "Тестовая компания" - IT услуги'
        };

        // Заполняем данные детей
        if (haveChildren) {
            randomData.FIOChildren1 = getRandomItem(names);
            randomData.dateOfBirthChildren1 = getRandomDate();
            randomData.phoneNumberChildren1 = getRandomPhone();
            randomData.placeOfStudyChildren1 = 'МГУ им. Ломоносова';
            randomData.placeOfLivingChildren1 = `г. ${getRandomItem(cities)}, ул. Студенческая, д. ${Math.floor(Math.random() * 100 + 1)}`;
        }

        // Заполняем данные родственников
        if (haveFamilyMembers) {
            randomData.FIORelative1 = `Отец - ${getRandomItem(names)}`;
            randomData.dateOfBirthRelative1 = getRandomDate();
            randomData.phoneNumberRelative1 = getRandomPhone();
            randomData.placeOfStudyRelative1 = 'ПАО "Газпром"';
            randomData.placeOfLivingRelative1 = `г. ${getRandomItem(cities)}, ул. Семейная, д. ${Math.floor(Math.random() * 100 + 1)}`;
        }

        setFormData(randomData);
        setPersonalDataChecked(true);
        
        // Случайно устанавливаем состояния
        setSurnameChanged(Math.random() > 0.5);
        setCriminalResponsibility(Math.random() > 0.8);
        setLegalEntity(Math.random() > 0.7);
        setMilitaryDuty(Math.random() > 0.3);
    };

    // Функция для получения токена из cookie
    const getAccessTokenFromCookie = () => {
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'access_token') {
                return value;
            }
        }
        return null;
    };

    // Функция для форматирования даты из dd.mm.yyyy в yyyy-mm-dd
    const formatDateForDatabase = (dateString) => {
        if (!dateString || dateString.trim() === '') {
            return null;
        }
        
        // Проверяем различные форматы даты
        const ddmmyyyyPattern = /^(\d{1,2})\.(\d{1,2})\.(\d{4})$/;
        const match = dateString.match(ddmmyyyyPattern);
        
        if (match) {
            const [, day, month, year] = match;
            const formattedDay = day.padStart(2, '0');
            const formattedMonth = month.padStart(2, '0');
            return `${year}-${formattedMonth}-${formattedDay}`;
        }
        
        // Если формат уже правильный yyyy-mm-dd
        const yyyymmddPattern = /^(\d{4})-(\d{1,2})-(\d{1,2})$/;
        if (yyyymmddPattern.test(dateString)) {
            return dateString;
        }
        
        console.warn(`Неверный формат даты: ${dateString}`);
        return null;
    };

    // Функция для сбора данных детей в JSON формате
    const collectChildrenData = () => {
        if (!haveChildren) {
            return null;
        }

        const children = [];
        
        // Основной ребенок
        if (formData.FIOChildren1) {
            children.push({
                full_name: formData.FIOChildren1 || '',
                birth_date: formatDateForDatabase(formData.dateOfBirthChildren1) || '',
                phone: formData.phoneNumberChildren1 || '',
                work_study_place: formData.placeOfStudyChildren1 || '',
                residence_address: formData.placeOfLivingChildren1 || ''
            });
        }

        // Дополнительные дети
        additionalChildrenTables.forEach(index => {
            if (formData[`FIOChildren${index}`]) {
                children.push({
                    full_name: formData[`FIOChildren${index}`] || '',
                    birth_date: formatDateForDatabase(formData[`dateOfBirthChildren${index}`]) || '',
                    phone: formData[`phoneNumberChildren${index}`] || '',
                    work_study_place: formData[`placeOfStudyChildren${index}`] || '',
                    residence_address: formData[`placeOfLivingChildren${index}`] || ''
                });
            }
        });

        return children.length > 0 ? children : null;
    };

    // Функция для сбора данных членов семьи в JSON формате
    const collectFamilyMembersData = () => {
        if (!haveFamilyMembers) {
            return null;
        }

        const familyMembers = [];
        
        // Основной член семьи
        if (formData.FIORelative1) {
            familyMembers.push({
                relationship_and_name: formData.FIORelative1 || '',
                birth_date: formatDateForDatabase(formData.dateOfBirthRelative1) || '',
                phone: formData.phoneNumberRelative1 || '',
                work_study_place: formData.placeOfStudyRelative1 || '',
                residence_address: formData.placeOfLivingRelative1 || ''
            });
        }

        // Дополнительные члены семьи
        additionalRelativeTables.forEach(index => {
            if (formData[`FIORelative${index}`]) {
                familyMembers.push({
                    relationship_and_name: formData[`FIORelative${index}`] || '',
                    birth_date: formatDateForDatabase(formData[`dateOfBirthRelative${index}`]) || '',
                    phone: formData[`phoneNumberRelative${index}`] || '',
                    work_study_place: formData[`placeOfStudyRelative${index}`] || '',
                    residence_address: formData[`placeOfLivingRelative${index}`] || ''
                });
            }
        });

        return familyMembers.length > 0 ? familyMembers : null;
    };

    // Функция для загрузки вакансий из API
    const loadVacancies = async () => {
        try {
            setIsLoadingVacancies(true);
            setVacancyError('');

            const accessToken = getAccessTokenFromCookie();
            
            if (!accessToken) {
                setVacancyError('Токен доступа не найден');
                return;
            }

            const response = await fetch('/api/v1/vacancy/', {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${accessToken}` 
                }
            });

            const data = await response.json();

            if (data.response && data.attributes) {
                // Преобразуем данные API в массив строк для select
                const vacancies = data.attributes.map(vacancy => vacancy.title);
                setVacancyOptions(vacancies);
                
                // Сохраняем полные данные для получения ключей
                window.vacanciesData = data.attributes;
                console.log('Вакансии загружены:', vacancies);
            } else {
                setVacancyError('Ошибка при получении данных вакансий');
            }
        } catch (error) {
            console.error('Ошибка при загрузке вакансий:', error);
            
            if (error.response) {
                if (error.response.status === 401) {
                    setVacancyError('Ошибка авторизации. Пожалуйста, войдите в систему заново.');
                } else if (error.response.status === 403) {
                    setVacancyError('Нет доступа к данным вакансий');
                } else {
                    setVacancyError(error.response.data?.error || 'Ошибка сервера при загрузке вакансий');
                }
            } else {
                setVacancyError('Ошибка при загрузке вакансий');
            }
        } finally {
            setIsLoadingVacancies(false);
        }
    };

    // Функция для загрузки семейного положения из API
    const loadMaritalStatuses = async () => {
        try {
            setIsLoadingMaritalStatuses(true);
            setMaritalStatusError('');

            const accessToken = getAccessTokenFromCookie();
            
            if (!accessToken) {
                setMaritalStatusError('Токен доступа не найден');
                return;
            }

            const response = await fetch('/api/v1/marital-statuses/', {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${accessToken}` 
                }
            });

            const data = await response.json();

            if (data.response && data.attributes) {
                // Преобразуем данные API в массив строк для select
                const maritalStatuses = data.attributes.map(status => status.title);
                setMaritalStatusApiOptions(maritalStatuses);
                
                // Сохраняем полные данные для получения ключей
                window.maritalStatusData = data.attributes;
                console.log('Семейное положение загружено:', maritalStatuses);
            } else {
                setMaritalStatusError('Ошибка при получении данных семейного положения');
            }
        } catch (error) {
            console.error('Ошибка при загрузке семейного положения:', error);
            
            if (error.response) {
                if (error.response.status === 401) {
                    setMaritalStatusError('Ошибка авторизации. Пожалуйста, войдите в систему заново.');
                } else if (error.response.status === 403) {
                    setMaritalStatusError('Нет доступа к данным семейного положения');
                } else {
                    setMaritalStatusError(error.response.data?.error || 'Ошибка сервера при загрузке семейного положения');
                }
            } else {
                setMaritalStatusError('Ошибка при загрузке семейного положения');
            }
        } finally {
            setIsLoadingMaritalStatuses(false);
        }
    };

    // Загружаем вакансии при монтировании компонента
    useEffect(() => {
        loadVacancies();
        loadMaritalStatuses();
    }, []);

    // Используем данные из API если они загружены, иначе статичные варианты
    const maritalStatusOptions = maritalStatusApiOptions.length > 0 ? maritalStatusApiOptions : [
        'Не женат/Не замужем',
        'Женат/Замужем',
        'В разводе',
        'Вдовец/Вдова',
        'Гражданский брак'
    ];

    // Функция для закрытия всех select'ов при клике вне их
    useEffect(() => {
        const handleClickOutside = () => {
            setShowVacancyOptions(false);
            setShowMaritalOptions(false);
        };

        document.addEventListener('click', handleClickOutside);
        return () => document.removeEventListener('click', handleClickOutside);
    }, []);

    // Функция для добавления таблицы члена семьи
    const addRelativeTable = () => {
        const newCounter = relativeCounter + 1;
        setRelativeCounter(newCounter);
        setAdditionalRelativeTables(prev => [...prev, newCounter]);
    };

    // Функция для добавления таблицы ребенка
    const addChildrenTable = () => {
        const newCounter = childrenCounter + 1;
        setChildrenCounter(newCounter);
        setAdditionalChildrenTables(prev => [...prev, newCounter]);
    };

    // Функция для получения ключа вакансии
    const getVacancyKey = (selectedTitle) => {
        if (window.vacanciesData) {
            const vacancy = window.vacanciesData.find(v => v.title === selectedTitle);
            return vacancy ? vacancy.key : '';
        }
        return '';
    };

    // Функция для получения ключа семейного положения
    const getMaritalStatusKey = (selectedTitle) => {
        if (window.maritalStatusData) {
            const status = window.maritalStatusData.find(s => s.title === selectedTitle);
            return status ? status.id : '';
        }
        return '';
    };

    // Функция для сбора данных формы (теперь использует centralized state)
    const collectFormData = () => {
        return formData;
    };

    // Функция для разделения ФИО
    const splitFullName = (fullName) => {
        const parts = fullName.trim().split(/\s+/);
        return {
            last_name: parts[0] || '',
            first_name: parts[1] || '',
            middle_name: parts[2] || ''
        };
    };

    // Функция для разделения серии и номера паспорта
    const splitPassportData = (passportSeriaNumber) => {
        const parts = passportSeriaNumber.replace(/\s+/g, ' ').trim().split(' ');
        return {
            passport_series: parts[0] || '',
            passport_number: parts[1] || ''
        };
    };

    // Функция для разделения адреса на страну и город
    const splitBirthPlace = (birthPlace) => {
        if (!birthPlace) return { country: '', city: '' };
        
        const parts = birthPlace.split(',').map(part => part.trim());
        return {
            country: parts[0] || '',
            city: parts.length > 1 ? parts.slice(1).join(', ') : parts[0] || ''
        };
    };

    // Функция для отправки формы
    const handleSubmit = async () => {
        try {
            setIsSubmitting(true);
            setSubmitError('');

            const accessToken = getAccessTokenFromCookie();
            
            if (!accessToken) {
                setSubmitError('Токен доступа не найден');
                return;
            }

            // Собираем данные формы
            const rawFormData = collectFormData();
            
            // Разделяем ФИО
            const nameData = splitFullName(rawFormData.FIO || '');
            
            // Разделяем паспортные данные
            const passportData = splitPassportData(rawFormData.passwordSeriaNumber || '');

            // Разделяем место рождения
            const birthPlaceData = splitBirthPlace(rawFormData.birthPlace);

            // Собираем данные детей и членов семьи
            const childrenData = collectChildrenData();
            const familyMembersData = collectFamilyMembersData();

            // Формируем данные для API
            const apiData = {
                vacancies_key: getVacancyKey(selectedVacancy),
                marital_statuses_key: getMaritalStatusKey(selectedMaritalStatus),
                status: "active", // По умолчанию
                first_name: nameData.first_name,
                last_name: nameData.last_name,
                middle_name: nameData.middle_name,
                reason_for_changing_surnames: surnameChanged ? (rawFormData.reasonOfChange || '') : null,
                birth_date: formatDateForDatabase(rawFormData.birthDate),
                country_birth: birthPlaceData.country,
                city_birth: birthPlaceData.city,
                mobile_phone_candidate: rawFormData.mobileNumber || '',
                home_phone_candidate: rawFormData.domesticNumber || '',
                mail_candidate: rawFormData.email || '',
                inn: rawFormData.INN || '',
                passport_series: passportData.passport_series,
                passport_number: passportData.passport_number,
                passport_issued: rawFormData.issuedBy || '',
                passport_issue_date: formatDateForDatabase(rawFormData.dateOfIssue),
                permanent_registration_address: rawFormData.adressOfPermanentReg || '',
                temporary_registration_address: rawFormData.adressOfTemporaryReg || '',
                actual_residence_address: rawFormData.adressOfFactialLiving || '',
                family_partner: selectedMaritalStatus === 'Женат/Замужем' ? (rawFormData.FIOSuprug || '') : null,
                adult_family_members: familyMembersData ? JSON.stringify(familyMembersData) : JSON.stringify([]),
                adult_children: childrenData ? JSON.stringify(childrenData) : JSON.stringify([]),
                serviceman: militaryDuty,
                law_breaker: criminalResponsibility ? (rawFormData.whyPrisoner || 'Да') : 'Нет',
                legal_entity: legalEntity ? (rawFormData.LegalEntity || 'Да') : 'Нет',
                is_data_processing: personalDataChecked,
                comment: 'Коммент'
            };

            console.log('Отправляемые данные:', apiData);

            const response = await fetch('/api/v1/candidates/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${accessToken}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(apiData)
            });

            const result = await response.json();

            if (response.ok) {
                setSubmitSuccess(true);
                console.log('Анкета успешно отправлена:', result);
                
            } else {
                console.error('Ошибка при отправке:', result);
                if (result.errors) {
                    // Формируем понятное сообщение об ошибках
                    const errorMessages = Object.values(result.errors).flat();
                    setSubmitError(`Ошибки в форме: ${errorMessages.slice(0, 3).join(', ')}${errorMessages.length > 3 ? '...' : ''}`);
                } else {
                    setSubmitError(result.message || 'Ошибка при отправке анкеты');
                }
            }
        } catch (error) {
            console.error('Ошибка при отправке анкеты:', error);
            setSubmitError('Ошибка соединения с сервером');
        } finally {
            setIsSubmitting(false);
        }
    };

    // Компонент кастомного select
    const CustomSelect = ({ options, placeholder, value, onChange, show, onToggle, onSelect, isLoading, error }) => (
        <div className="custom-select" style={{width: '100%'}}>
            <div 
                className={`select-selected ${show ? 'select-arrow-active' : ''}`}
                onClick={(e) => {
                    e.stopPropagation();
                    if (!isLoading) {
                        onToggle();
                    }
                }}
                style={{
                    opacity: isLoading ? 0.6 : 1,
                    cursor: isLoading ? 'not-allowed' : 'pointer'
                }}
            >
                {isLoading ? 'Загрузка...' : (error ? 'Ошибка загрузки' : (value || placeholder))}
            </div>
            {!isLoading && !error && (
                <div className={`select-items ${!show ? 'select-hide' : ''}`}>
                    {options.map((option, index) => (
                        <div 
                            key={index}
                            className={value === option ? 'same-as-selected' : ''}
                            onClick={() => onSelect(option)}
                        >
                            {option}
                        </div>
                    ))}
                </div>
            )}
        </div>
    );

    return (
        <>
            <header>
                <img src="img/Logo с текстом.png" alt="Картинка с логотипом агенства и подписью Поиск метров" />
            </header>

            <article>
                <h1>Анкета кандидата</h1>
                <p>Заполните анкету, чтобы подать заявку на вакансию</p>
            </article>

            <main>
                <section>
                    <div className="center-card big">
                        {/* Кнопка автозаполнения для отладки */}
                        <div className="formRow" style={{marginBottom: '20px', backgroundColor: '#f8f9fa', padding: '15px', borderRadius: '8px', border: '2px dashed #6c757d'}}>
                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                                <div>
                                    <h4 style={{ margin: '0 0 5px 0', color: '#6c757d' }}>🛠️ Режим отладки</h4>
                                    <p style={{ margin: 0, fontSize: '14px', color: '#6c757d' }}>Автоматически заполнить форму случайными данными для тестирования</p>
                                </div>
                                <button 
                                    onClick={generateRandomData}
                                    style={{
                                        backgroundColor: '#007bff',
                                        color: 'white',
                                        border: 'none',
                                        padding: '10px 20px',
                                        borderRadius: '5px',
                                        cursor: 'pointer',
                                        fontSize: '14px',
                                        fontWeight: 'bold',
                                        transition: 'background-color 0.3s'
                                    }}
                                    onMouseOver={(e) => e.target.style.backgroundColor = '#0056b3'}
                                    onMouseOut={(e) => e.target.style.backgroundColor = '#007bff'}
                                >
                                    🎲 Заполнить случайными данными
                                </button>
                            </div>
                        </div>

                        <h1>Общие сведения</h1>
                        <p>Мы не передаём эти данные третьим лицам и используем их только для целей адаптации и сопровождения кандидатов</p>

                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="Vacancy" className="formLabel">Вакансия</label>
                                <CustomSelect
                                    options={vacancyOptions}
                                    placeholder="Выберите вакансию, на которую подаетесь"
                                    value={selectedVacancy}
                                    show={showVacancyOptions}
                                    isLoading={isLoadingVacancies}
                                    error={vacancyError}
                                    onToggle={() => {
                                        setShowVacancyOptions(!showVacancyOptions);
                                        setShowMaritalOptions(false);
                                    }}
                                    onSelect={(option) => {
                                        setSelectedVacancy(option);
                                        setShowVacancyOptions(false);
                                    }}
                                />
                                {vacancyError && (
                                    <div className="error-message" style={{ marginTop: '5px', fontSize: '14px', color: '#e74c3c' }}>
                                        {vacancyError}
                                        <button
                                            onClick={loadVacancies}
                                            style={{
                                                marginLeft: '10px',
                                                background: 'none',
                                                border: 'none',
                                                color: '#3498db',
                                                cursor: 'pointer',
                                                textDecoration: 'underline'
                                            }}
                                        >
                                            Повторить
                                        </button>
                                    </div>
                                )}
                            </div>
                        </div>

                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="FIO" className="formLabel">ФИО</label>
                                <input 
                                    type="text" 
                                    name="FIO" 
                                    className="formInput big" 
                                    placeholder="Иванов Иван Иванович" 
                                    value={formData.FIO || ''}
                                    onChange={(e) => handleFormDataChange('FIO', e.target.value)}
                                />
                            </div>
                        </div>

                        <div className="formRow justify-flex-start">
                            <div className="input-container big">
                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="surnameChanged" 
                                        checked={surnameChanged}
                                        onChange={() => setSurnameChanged(true)}
                                    />
                                    <span className="radiomark"></span>
                                    Я менял(-а) фамилию
                                </label>

                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="surnameChanged" 
                                        checked={!surnameChanged}
                                        onChange={() => setSurnameChanged(false)}
                                    />
                                    <span className="radiomark"></span>
                                    Я не менял(-а) фамилию
                                </label>
                            </div>
                        </div>

                        {surnameChanged && (
                            <div className="toggle-block" style={{width: '100%'}}>
                                <div className="formRow">
                                    <div className="input-container">
                                        <label htmlFor="reasonOfChange" className="formLabel">Причина изменения фамилии</label>
                                        <input 
                                            type="text" 
                                            name="reasonOfChange" 
                                            className="formInput big" 
                                            placeholder="Опишите, почему поменяли фамилию" 
                                            value={formData.reasonOfChange || ''}
                                            onChange={(e) => handleFormDataChange('reasonOfChange', e.target.value)}
                                        />
                                    </div>
                                </div>
                            </div>
                        )}

                        <div className="formRow justify-space-between">
                            <div className="input-container w-49">
                                <label htmlFor="birthDate" className="formLabel">Дата рождения</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="text" 
                                    name="birthDate" 
                                    className="formInput" 
                                    placeholder="01.01.1990" 
                                    value={formData.birthDate || ''}
                                    onChange={(e) => handleFormDataChange('birthDate', e.target.value)}
                                />
                            </div>

                            <div className="input-container w-49">
                                <label htmlFor="birthPlace" className="formLabel">Место рождения</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="text" 
                                    name="birthPlace" 
                                    className="formInput" 
                                    placeholder="Страна, город" 
                                    value={formData.birthPlace || ''}
                                    onChange={(e) => handleFormDataChange('birthPlace', e.target.value)}
                                />
                            </div>
                        </div>

                        <div className="formRow justify-space-between">
                            <div className="input-container w-49">
                                <label htmlFor="mobileNumber" className="formLabel">Мобильный телефон</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="tel" 
                                    name="mobileNumber" 
                                    className="formInput" 
                                    placeholder="+7(999)999-99-99" 
                                    value={formData.mobileNumber || ''}
                                    onChange={(e) => handleFormDataChange('mobileNumber', e.target.value)}
                                />
                            </div>

                            <div className="input-container w-49">
                                <label htmlFor="domesticNumber" className="formLabel">Домашний телефон</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="tel" 
                                    name="domesticNumber" 
                                    className="formInput" 
                                    placeholder="999 999" 
                                    value={formData.domesticNumber || ''}
                                    onChange={(e) => handleFormDataChange('domesticNumber', e.target.value)}
                                />
                            </div>
                        </div>

                        <div className="formRow justify-space-between">
                            <div className="input-container w-49">
                                <label htmlFor="email" className="formLabel">E-mail</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="email" 
                                    name="email" 
                                    className="formInput" 
                                    placeholder="example@gmail.com" 
                                    value={formData.email || ''}
                                    onChange={(e) => handleFormDataChange('email', e.target.value)}
                                />
                            </div>

                            <div className="input-container w-49">
                                <label htmlFor="INN" className="formLabel">ИНН</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="number" 
                                    name="INN" 
                                    className="formInput" 
                                    placeholder="123456789012" 
                                    value={formData.INN || ''}
                                    onChange={(e) => handleFormDataChange('INN', e.target.value)}
                                />
                            </div>
                        </div>

                        <div className="formRow" style={{marginTop: '50px'}}>
                            <h3>Паспортные данные</h3>
                        </div>

                        <div className="formRow justify-space-between">
                            <div className="input-container w-49">
                                <label htmlFor="passwordSeriaNumber" className="formLabel">Серия и номер</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="tel" 
                                    name="passwordSeriaNumber" 
                                    className="formInput" 
                                    placeholder="1234 567890" 
                                    value={formData.passwordSeriaNumber || ''}
                                    onChange={(e) => handleFormDataChange('passwordSeriaNumber', e.target.value)}
                                />
                            </div>

                            <div className="input-container w-49">
                                <label htmlFor="dateOfIssue" className="formLabel">Дата выдачи</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="tel" 
                                    name="dateOfIssue" 
                                    className="formInput" 
                                    placeholder="01.01.1990" 
                                    value={formData.dateOfIssue || ''}
                                    onChange={(e) => handleFormDataChange('dateOfIssue', e.target.value)}
                                />
                            </div>
                        </div>

                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="issuedBy" className="formLabel">Кем выдан</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="text" 
                                    name="issuedBy" 
                                    className="formInput" 
                                    placeholder="ОФУМС России" 
                                    value={formData.issuedBy || ''}
                                    onChange={(e) => handleFormDataChange('issuedBy', e.target.value)}
                                />
                            </div>
                        </div>

                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="adressOfPermanentReg" className="formLabel">Адрес постоянной регистрации</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="text" 
                                    name="adressOfPermanentReg" 
                                    className="formInput" 
                                    placeholder="Адрес постоянной регистрации" 
                                    value={formData.adressOfPermanentReg || ''}
                                    onChange={(e) => handleFormDataChange('adressOfPermanentReg', e.target.value)}
                                />
                            </div>
                        </div>

                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="adressOfTemporaryReg" className="formLabel">Адрес временной регистрации</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="text" 
                                    name="adressOfTemporaryReg" 
                                    className="formInput" 
                                    placeholder="Адрес временной регистрации" 
                                    value={formData.adressOfTemporaryReg || ''}
                                    onChange={(e) => handleFormDataChange('adressOfTemporaryReg', e.target.value)}
                                />
                            </div>
                        </div>

                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="adressOfFactialLiving" className="formLabel">Адрес фактического проживания</label>
                                <input 
                                    style={{width: '100%'}} 
                                    type="text" 
                                    name="adressOfFactialLiving" 
                                    className="formInput" 
                                    placeholder="Адрес фактического проживания" 
                                    value={formData.adressOfFactialLiving || ''}
                                    onChange={(e) => handleFormDataChange('adressOfFactialLiving', e.target.value)}
                                />
                            </div>
                        </div>

                        <div className="formRow flex-direction-column" style={{marginTop: '50px'}}>
                            <h3>Состав семьи</h3>
                            <h4>Заполните эти данные, чтобы мы могли предложить вам подходящие условия</h4>
                        </div>

                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="maritalStatus" className="formLabel">Семейное положение</label>
                                <CustomSelect
                                    options={maritalStatusOptions}
                                    placeholder="Выберите ваше семейное положение"
                                    value={selectedMaritalStatus}
                                    show={showMaritalOptions}
                                    isLoading={isLoadingMaritalStatuses}
                                    error={maritalStatusError}
                                    onToggle={() => {
                                        setShowMaritalOptions(!showMaritalOptions);
                                        setShowVacancyOptions(false);
                                    }}
                                    onSelect={(option) => {
                                        setSelectedMaritalStatus(option);
                                        setShowMaritalOptions(false);
                                    }}
                                />
                                {maritalStatusError && (
                                    <div className="error-message" style={{ marginTop: '5px', fontSize: '14px', color: '#e74c3c' }}>
                                        {maritalStatusError}
                                        <button
                                            onClick={loadMaritalStatuses}
                                            style={{
                                                marginLeft: '10px',
                                                background: 'none',
                                                border: 'none',
                                                color: '#3498db',
                                                cursor: 'pointer',
                                                textDecoration: 'underline'
                                            }}
                                        >
                                            Повторить
                                        </button>
                                    </div>
                                )}
                            </div>
                        </div>

                        <SpouseTable 
                            formData={formData} 
                            setFormData={setFormData} 
                            isVisible={selectedMaritalStatus === 'Женат/Замужем'} 
                        />

                        <div className="formRow flex-direction-column">
                            <h3>1. Дети старше 18 лет</h3>
                        </div>

                        <div className="formRow justify-flex-start">
                            <div className="input-container big">
                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="haveChildren" 
                                        checked={haveChildren}
                                        onChange={() => setHaveChildren(true)}
                                    />
                                    <span className="radiomark"></span>
                                    У меня есть дети старше 18 лет
                                </label>

                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="haveChildren" 
                                        checked={!haveChildren}
                                        onChange={() => setHaveChildren(false)}
                                    />
                                    <span className="radiomark"></span>
                                    У меня нет детей старше 18 лет
                                </label>
                            </div>
                        </div>

                        {haveChildren && (
                            <div className="toggle-block" style={{width: '100%'}}>
                                <ChildrenTable index={1} formData={formData} setFormData={setFormData} />
                                
                                {additionalChildrenTables.map(index => (
                                    <ChildrenTable key={index} index={index} formData={formData} setFormData={setFormData} />
                                ))}

                                <div className="formRow" style={{marginBottom: 0}}>
                                    <button className="bigFormButton" onClick={addChildrenTable}>
                                        <div className="textCont"></div>
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19M5 12H19" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                        </svg>
                                        Добавить совершеннолетнего ребенка
                                    </button>
                                </div>
                                <div className="formRow justify-flex-start" style={{marginTop: '10px'}}>
                                    <p style={{marginTop: 0}}>Добавьте всех имеющихся детей</p>
                                </div>
                            </div>
                        )}

                        <div className="formRow flex-direction-column">
                            <h3>2. Члены семьи старше 18 лет</h3>
                        </div>

                        <div className="formRow justify-flex-start">
                            <div className="input-container big">
                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="familyMembers" 
                                        checked={haveFamilyMembers}
                                        onChange={() => setHaveFamilyMembers(true)}
                                    />
                                    <span className="radiomark"></span>
                                    У меня есть члены семьи (родители/братья/сестры) старше 18 лет
                                </label><br />

                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="familyMembers" 
                                        checked={!haveFamilyMembers}
                                        onChange={() => setHaveFamilyMembers(false)}
                                    />
                                    <span className="radiomark"></span>
                                    У меня нет членов семьи (родители/братья/сестры) старше 18 лет
                                </label>
                            </div>
                        </div>

                        {haveFamilyMembers && (
                            <div className="toggle-block" style={{width: '100%'}}>
                                <RelativeTable index={1} formData={formData} setFormData={setFormData} />
                                
                                {additionalRelativeTables.map(index => (
                                    <RelativeTable key={index} index={index} formData={formData} setFormData={setFormData} />
                                ))}

                                <div className="formRow" style={{marginBottom: 0}}>
                                    <button className="bigFormButton" onClick={addRelativeTable}>
                                        <div className="textCont"></div>
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19M5 12H19" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                        </svg>
                                        Добавить члена семьи
                                    </button>
                                </div>
                                <div className="formRow justify-flex-start" style={{marginTop: '10px', marginLeft: '30px'}}>
                                    <p style={{marginTop: 0}}>Добавьте всех ближайших совершеннолетних членов семьи: родителей, братьев/сестер</p>
                                </div>
                            </div>
                        )}

                        <div className="formRow flex-direction-column" style={{marginTop: '50px'}}>
                            <h3>Юридический статус</h3>
                            <h4>Ответьте на следующие вопросы, которые помогут нам оценить ваше соответствие вакансии</h4>
                        </div>

                        <div className="formRow justify-flex-start">
                            <p style={{marginTop: 0, color: '#181817', fontSize: '18px'}}>1. Являетесь ли военнообязанным(-ой)?</p>
                        </div>
                        <div className="formRow justify-flex-start" style={{marginTop: 0, fontSize: '18px'}}>
                            <div className="input-container big">
                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="militaryDuty" 
                                        checked={militaryDuty}
                                        onChange={() => setMilitaryDuty(true)}
                                    />
                                    <span className="radiomark"></span>
                                    Да, являюсь
                                </label>

                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="militaryDuty" 
                                        checked={!militaryDuty}
                                        onChange={() => setMilitaryDuty(false)}
                                    />
                                    <span className="radiomark"></span>
                                    Нет, не являюсь
                                </label>
                            </div>
                        </div>

                        <div className="formRow justify-flex-start" style={{marginTop: '50px'}}>
                            <p style={{marginTop: 0, color: '#181817', fontSize: '18px'}}>2. Привлекались ли вы когда-либо к уголовной ответственности?</p>
                        </div>
                        <div className="formRow justify-flex-start" style={{marginTop: 0, fontSize: '18px'}}>
                            <div className="input-container big">
                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="criminalResponsibility" 
                                        checked={criminalResponsibility}
                                        onChange={() => setCriminalResponsibility(true)}
                                    />
                                    <span className="radiomark"></span>
                                    Да, привлекался
                                </label>

                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="criminalResponsibility" 
                                        checked={!criminalResponsibility}
                                        onChange={() => setCriminalResponsibility(false)}
                                    />
                                    <span className="radiomark"></span>
                                    Нет, не привлекался
                                </label>
                            </div>
                        </div>

                        {criminalResponsibility && (
                            <div className="toggle-block" style={{width: '100%'}}>
                                <div className="formRow">
                                    <div className="input-container">
                                        <label htmlFor="whyPrisoner" className="formLabel">Причины привлечения</label>
                                        <input 
                                            style={{width: '100%'}} 
                                            type="text" 
                                            name="whyPrisoner" 
                                            className="formInput" 
                                            placeholder="Опишите, за что привлекались к ответственности" 
                                            value={formData.whyPrisoner || ''}
                                            onChange={(e) => handleFormDataChange('whyPrisoner', e.target.value)}
                                        />
                                    </div>
                                </div>
                            </div>
                        )}

                        <div className="formRow justify-flex-start" style={{marginTop: '50px'}}>
                            <p style={{marginTop: 0, color: '#181817', fontSize: '18px'}}>3. Являетесь ли вы (со-)учредителем юридического лица?</p>
                        </div>
                        <div className="formRow justify-flex-start" style={{marginTop: 0, fontSize: '18px'}}>
                            <div className="input-container big">
                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="legalEntity" 
                                        checked={legalEntity}
                                        onChange={() => setLegalEntity(true)}
                                    />
                                    <span className="radiomark"></span>
                                    Да, являюсь
                                </label>

                                <label className="custom-radio">
                                    <input 
                                        type="radio" 
                                        name="legalEntity" 
                                        checked={!legalEntity}
                                        onChange={() => setLegalEntity(false)}
                                    />
                                    <span className="radiomark"></span>
                                    Нет, не являюсь
                                </label>
                            </div>
                        </div>

                        {legalEntity && (
                            <div className="toggle-block" style={{width: '100%'}}>
                                <div className="formRow">
                                    <div className="input-container">
                                        <label htmlFor="LegalEntityActivity" className="formLabel">Укажите наименование и сферу деятельности</label>
                                        <input 
                                            style={{width: '100%'}} 
                                            type="text" 
                                            name="LegalEntity" 
                                            className="formInput" 
                                            placeholder="Наименование и сфера деятельности юрлица" 
                                            value={formData.LegalEntity || ''}
                                            onChange={(e) => handleFormDataChange('LegalEntity', e.target.value)}
                                        />
                                    </div>
                                </div>
                            </div>
                        )}

                        <div className="checkboxRow" style={{maxWidth: 'none', alignItems: 'center'}}>
                            <label className="custom-checkbox" htmlFor="personalData">
                                <input 
                                    type="checkbox" 
                                    name="personalData" 
                                    id="personalData"
                                    checked={personalDataChecked}
                                    onChange={(e) => setPersonalDataChecked(e.target.checked)}
                                />
                                <span className="checkmark"></span>
                            </label>
                            <label htmlFor="personalData">Я даю согласие на обработку <span>своих персональных данных</span></label>
                        </div>

                        <div className="formRow" style={{marginTop: '0px'}}>
                            <button 
                                className={personalDataChecked ? "formBtn btn-active" : "formBtn btn-inactive"}
                                disabled={!personalDataChecked || isSubmitting}
                                onClick={handleSubmit}
                            >
                                {isSubmitting ? 'Отправка...' : 'Отправить анкету'}
                            </button>
                        </div>

                        {submitError && (
                            <div className="formRow">
                                <div style={{ color: '#e74c3c', fontSize: '14px', marginTop: '10px' }}>
                                    {submitError}
                                </div>
                            </div>
                        )}

                        {submitSuccess && (
                            <div className="formRow">
                                <div style={{ color: '#27ae60', fontSize: '14px', marginTop: '10px' }}>
                                    Анкета успешно отправлена! Спасибо за заявку.
                                </div>
                            </div>
                        )}
                    </div>
                </section>
            </main>
        </>
    );
}


// ======================!!!!!!!!!!!!!!Авторизация безопасника!!!!!!!!!!!!========================



//  function SecurityRegForm() {
//     const [loginValue, setLoginValue] = useState('');
//     const [passwordValue, setPasswordValue] = useState('');
//     const [showLoginCheckmark, setShowLoginCheckmark] = useState(false);
//     const [showPasswordCheckmark, setShowPasswordCheckmark] = useState(false);
//     const [isLoading, setIsLoading] = useState(false);
//     const [error, setError] = useState('');
//     const [isAuthenticated, setIsAuthenticated] = useState(false);
//     const [authResult, setAuthResult] = useState(null);

//     const handleLoginChange = (e) => {
//         const value = e.target.value;
//         setLoginValue(value);
//         setShowLoginCheckmark(value.trim().length > 0);
        
//         // Очищаем ошибку при изменении значения
//         if (error) {
//             setError('');
//         }
//     };

//     const handlePasswordChange = (e) => {
//         const value = e.target.value;
//         setPasswordValue(value);
//         setShowPasswordCheckmark(value.trim().length > 0);
        
//         // Очищаем ошибку при изменении значения
//         if (error) {
//             setError('');
//         }
//     };

//     const isFormValid = () => {
//         return loginValue.trim().length > 0 && passwordValue.trim().length > 0;
//     };

//     // Функция для отправки запроса на авторизацию администратора
//     const sendAuthRequest = async (login, password) => {
//         console.log('sendAuthRequest вызвана для администратора');
//         console.log('Данные для отправки:', { login, password: '***' }); // Пароль скрываем в логах
        
//         try {
//             setIsLoading(true);
//             setError('');
            
//             console.log('Отправляем запрос на авторизацию администратора...');
            
//             // Отправляем запрос на аутентификацию (используем тот же endpoint)
//             const response = await axios.post('/api/v1/account/auth', {
//                 email: login,
//                 password: password
//             }, {
//                 headers: {
//                     'Content-Type': 'application/json'
//                 }
//             });

//             console.log('Ответ сервера:', response.data);
            
//             // Проверяем успешность запроса
//             if (response.data.request && response.data.attributes) {
//                 setAuthResult(response.data.attributes);
//                 setIsAuthenticated(true);
//                 console.log('Авторизация администратора успешна:', response.data);
                
//                 // Сохраняем токен в cookie
//                 if (response.data.attributes.access_token) {
//                     // Устанавливаем cookie с токеном на 30 дней
//                     const expirationDate = new Date();
//                     expirationDate.setTime(expirationDate.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30 дней
//                     document.cookie = `access_token=${response.data.attributes.access_token}; expires=${expirationDate.toUTCString()}; path=/; SameSite=Strict`;
//                     console.log('Токен администратора сохранен в cookie:', response.data.attributes.access_token);
//                 }
                
//                 return true;
//             } else {
//                 console.log('Ошибка в ответе сервера');
//                 setError('Ошибка при авторизации');
//                 return false;
//             }
//         } catch (error) {
//             console.error('Ошибка при авторизации администратора:', error);
            
//             if (error.response) {
//                 // Сервер ответил с кодом ошибки
//                 console.log('Ошибка ответа сервера:', error.response.status, error.response.data);
//                 if (error.response.status === 401) {
//                     setError('Неверный логин или пароль');
//                 } else if (error.response.status === 404) {
//                     setError('Пользователь не найден');
//                 } else if (error.response.status === 403) {
//                     setError('Доступ запрещен');
//                 } else {
//                     setError(error.response.data?.error || 'Ошибка сервера');
//                 }
//             } else if (error.request) {
//                 // Запрос был отправлен, но ответа не получено
//                 console.log('Ошибка запроса:', error.request);
//                 setError('Ошибка соединения с сервером');
//             } else {
//                 // Ошибка при настройке запроса
//                 console.log('Ошибка настройки:', error.message);
//                 setError('Ошибка при отправке запроса');
//             }
//             return false;
//         } finally {
//             console.log('Завершение sendAuthRequest для администратора');
//             setIsLoading(false);
//         }
//     };

//     const handleSubmit = async (e) => {
//         e.preventDefault();
//         console.log('Форма отправлена с данными:', { login: loginValue, password: '***' });
        
//         if (isFormValid()) {
//             console.log('Форма валидна, отправляем запрос авторизации...');
//             await sendAuthRequest(loginValue.trim(), passwordValue.trim());
//         } else {
//             console.log('Форма не валидна');
//             setError('Заполните все поля');
//         }
//     };

//     const handleLogout = () => {
//         console.log('Выход из системы администратора');
//         setIsAuthenticated(false);
//         setAuthResult(null);
//         setLoginValue('');
//         setPasswordValue('');
//         setShowLoginCheckmark(false);
//         setShowPasswordCheckmark(false);
//         setError('');
        
//         // Удаляем токен из cookie
//         document.cookie = 'access_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
//         console.log('Токен удален из cookie');
//     };

//     // Если администратор аутентифицирован, показываем результат
//     if (isAuthenticated && authResult) {
//         return (
//             <>
//                 <header>
//                     <img src="img/Logo с текстом.png" alt="Картинка с логотипом агенства и подписью Поиск метров" />
//                 </header>

//                 <main>
//                     <section>
//                         <div className="center-card">
//                             <h1>Авторизация успешна!</h1>
//                             <p>Добро пожаловать в административную панель</p>
                            
//                             <div style={{marginTop: '20px', padding: '15px', backgroundColor: '#f5f5f5', borderRadius: '8px'}}>
//                                 <strong>Информация о пользователе:</strong><br />
//                                 ID: {authResult.user.id}<br />
//                                 Роль: {authResult.user.role}<br />
//                                 {authResult.user.phone && (
//                                     <>Телефон: {authResult.user.phone}<br /></>
//                                 )}
//                                 {authResult.user.login && (
//                                     <>Логин: {authResult.user.login}<br /></>
//                                 )}
//                                 Токен: {authResult.access_token.substring(0, 20)}...
//                             </div>
                            
//                             <button 
//                                 className="formBtn btn-active"
//                                 onClick={handleLogout}
//                                 style={{marginTop: '20px'}}
//                             >
//                                 Выйти
//                             </button>
//                         </div>
//                     </section>
//                 </main>
//             </>
//         );
//     }

//     return (
//         <>
//             <header>
//                 <img src="img/Logo с текстом.png" alt="Картинка с логотипом агенства и подписью Поиск метров" />
//             </header>

//             <main>
//                 <section>
//                     <div className="center-card">
//                         <h1>Вход для администратора</h1>
//                         <p>Введите логин и пароль, чтобы авторизоваться в системе и получить доступ к административной панели</p>
                        
//                         <form action="#" style={{marginTop: '30px'}} onSubmit={handleSubmit}>
//                             <div className="input-container" style={{marginBottom: '20px'}}>
//                                 <label htmlFor="login" id="formLabel" className="formLabel">Логин</label>
//                                 <input 
//                                     type="text" 
//                                     name="login" 
//                                     id="login" 
//                                     className="formInput" 
//                                     placeholder="Введите логин"
//                                     value={loginValue}
//                                     onChange={handleLoginChange}
//                                     disabled={isLoading}
//                                 />
//                                 {showLoginCheckmark && (
//                                     <div className="checkmark-icon" id="checkmarkIcon">
//                                         <svg viewBox="0 0 24 24">
//                                             <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
//                                         </svg>
//                                     </div>
//                                 )}
//                             </div>
                            
//                             <div className="input-container">
//                                 <label htmlFor="password" className="formLabel">Пароль</label>
//                                 <input 
//                                     type="password" 
//                                     name="password" 
//                                     id="password" 
//                                     className="formInput" 
//                                     placeholder="Введите пароль"
//                                     value={passwordValue}
//                                     onChange={handlePasswordChange}
//                                     disabled={isLoading}
//                                 />
//                                 {showPasswordCheckmark && (
//                                     <div className="checkmark-icon" id="checkmarkIcon">
//                                         <svg viewBox="0 0 24 24">
//                                             <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
//                                         </svg>
//                                     </div>
//                                 )}
//                             </div>

//                             {/* Отображение ошибки */}
//                             {error && (
//                                 <div className="error-message" style={{
//                                     color: '#d32f2f',
//                                     fontSize: '14px',
//                                     marginTop: '10px',
//                                     padding: '8px',
//                                     backgroundColor: '#ffebee',
//                                     border: '1px solid #ffcdd2',
//                                     borderRadius: '4px'
//                                 }}>
//                                     {error}
//                                 </div>
//                             )}
                            
//                             <button 
//                                 className={isFormValid() && !isLoading ? "formBtn btn-active" : "formBtn btn-inactive"}
//                                 disabled={!isFormValid() || isLoading}
//                                 type="submit"
//                                 style={{marginTop: '20px'}}
//                             >
//                                 {isLoading ? "Вход..." : "Войти"}
//                             </button><br />
//                         </form>
                        
//                         <a href="#" style={{display: 'none'}} id="changeNumber">Изменить номер</a>
//                     </div>
//                 </section>
//             </main>
//         </>
//     );
// }


 // Header Component
        function Header() {
            return (
                <header>
                    <div className="formRow justify-space-between w-80">
                        <div style={{display: 'flex', alignItems: 'center'}}>
                            <img id="nonTextImg" src="img/ logo без текста.png" alt="Логотип компании Поиск Метров" />
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
                            <button id="notifBtn"><img src="img/ring.png" alt="Уведомлений нет" /></button>
                            <button id="exitBtn">Выйти из ЛК <img src="img/arowRight.png" alt="Стрелочка вправо" /></button>
                        </div>
                    </div>
                </header>
            );
        }

function CandidatesTable({ onFiltersClick, onRowClick, filtersButtonRef }) {
    const [candidates, setCandidates] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [selectedKeys, setSelectedKeys] = useState([]); // Массив для хранения выбранных ключей
    const [pagination, setPagination] = useState({
        current_page: 1,
        last_page: 1,
        total: 0,
        per_page: 8,
        from: 0,
        to: 0
    });

    const [isFormatDropdownOpen, setIsFormatDropdownOpen] = useState(false);
    const [selectedFormat, setSelectedFormat] = useState('.xlsx');
    const [downloadLoading, setDownloadLoading] = useState(false); // Состояние загрузки для кнопки скачивания

    // Функция для получения токена из cookie
    const getAccessToken = () => {
        const cookies = document.cookie.split(';');
        const tokenCookie = cookies.find(cookie => cookie.trim().startsWith('access_token='));
        return tokenCookie ? tokenCookie.split('=')[1] : null;
    };

    // Функция для получения CSRF токена (если необходимо)
    const getCsrfToken = () => {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : null;
    };

    // Функция для обработки изменения чекбокса
    const handleCheckboxChange = (vacancyKey, isChecked) => {
        setSelectedKeys(prev => {
            if (isChecked) {
                // Добавляем ключ, если его еще нет в массиве
                return prev.includes(vacancyKey) ? prev : [...prev, vacancyKey];
            } else {
                // Удаляем ключ из массива
                return prev.filter(key => key !== vacancyKey);
            }
        });
    };

    // Функция для обработки "Выбрать всех"
    const handleSelectAll = () => {
        const allVacancyKeys = candidates.map(candidate => candidate.vacancyKey);
        const allSelected = allVacancyKeys.every(key => selectedKeys.includes(key));
        
        if (allSelected) {
            // Если все выбраны, снимаем все
            setSelectedKeys(prev => prev.filter(key => !allVacancyKeys.includes(key)));
        } else {
            // Если не все выбраны, выбираем все
            setSelectedKeys(prev => {
                const newKeys = allVacancyKeys.filter(key => !prev.includes(key));
                return [...prev, ...newKeys];
            });
        }
    };

    // Функция для скачивания файлов
    const handleDownload = async () => {
        setDownloadLoading(true);
        
        try {
            const token = getAccessToken();
            if (!token) {
                throw new Error('Токен авторизации не найден');
            }

            // Определяем URL в зависимости от выбранного формата
            const endpoint = selectedFormat === '.pdf' ? 'pdf-format' : 'xlsx-format';
            let url = `http://127.0.0.1:8000/api/v1/export/${endpoint}`;
            
            // Добавляем параметр keys только если есть выбранные ключи
            if (selectedKeys.length > 0) {
                const keysParam = selectedKeys.join(',');
                url += `?keys=${encodeURIComponent(keysParam)}`;
            }

            const headers = {
                'accept': 'application/json',
                'Authorization': `Bearer ${token}`
            };

            // Добавляем CSRF токен если доступен
            const csrfToken = getCsrfToken();
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }

            const response = await fetch(url, {
                method: 'GET',
                headers: headers
            });

            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Неавторизован. Пожалуйста, войдите в систему');
                } else if (response.status === 403) {
                    throw new Error('Доступ запрещен');
                } else if (response.status === 404) {
                    throw new Error('Файл не найден или некорректные ключи');
                } else {
                    throw new Error(`Ошибка сервера: ${response.status}`);
                }
            }

            // Получаем blob данные
            const blob = await response.blob();
            
            // Создаем ссылку для скачивания
            const downloadUrl = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = downloadUrl;
            
            // Устанавливаем имя файла
            const fileName = selectedKeys.length > 0 
                ? `candidates_export_${new Date().toISOString().split('T')[0]}${selectedFormat}`
                : `all_candidates_export_${new Date().toISOString().split('T')[0]}${selectedFormat}`;
            link.download = fileName;
            
            // Добавляем ссылку в DOM, кликаем и удаляем
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Освобождаем память
            window.URL.revokeObjectURL(downloadUrl);
            
            const exportMessage = selectedKeys.length > 0 
                ? `Успешно скачано ${selectedKeys.length} анкет в формате ${selectedFormat}`
                : `Успешно скачаны все анкеты в формате ${selectedFormat}`;
            console.log(exportMessage);
            
        } catch (err) {
            console.error('Ошибка при скачивании:', err);
            alert(`Ошибка при скачивании: ${err.message}`);
        } finally {
            setDownloadLoading(false);
        }
    };

    // Функция для загрузки кандидатов
    const fetchCandidates = async (page = 1) => {
        setLoading(true);
        setError('');

        try {
            const token = getAccessToken();
            if (!token) {
                throw new Error('Токен авторизации не найден');
            }

            const headers = {
                'accept': '*/*',
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            };

            // Добавляем CSRF токен если доступен
            const csrfToken = getCsrfToken();
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }

            const response = await fetch(`http://127.0.0.1:8000/api/v1/candidates/?page=${page}`, {
                method: 'GET',
                headers: headers
            });

            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Неавторизован. Пожалуйста, войдите в систему');
                } else if (response.status === 403) {
                    throw new Error('Доступ запрещен');
                } else {
                    throw new Error(`Ошибка сервера: ${response.status}`);
                }
            }

            const data = await response.json();

            if (data.response && data.attributes) {
                // Преобразуем данные из API в формат для таблицы
                const transformedCandidates = data.attributes.data.map(candidate => ({
                    id: candidate.id,
                    name: `${candidate.last_name} ${candidate.first_name} ${candidate.middle_name || ''}`.trim(),
                    datetime: formatDateTime(candidate.created_at || new Date().toISOString()),
                    vacancy: candidate.vacancy?.attributes?.title || 'Не указана',
                    status: candidate.status || 'Не определен',
                    statusID: getStatusId(candidate.status),
                    hasVacancyComment: candidate.comment,
                    vacancyKey: candidate.key,
                    // Добавляем все данные кандидата для передачи в форму
                    fullData: candidate
                }));

                setCandidates(transformedCandidates);
                setPagination({
                    current_page: data.attributes.current_page,
                    last_page: data.attributes.last_page,
                    total: data.attributes.total,
                    per_page: data.attributes.per_page,
                    from: data.attributes.from,
                    to: data.attributes.to
                });
            } else {
                throw new Error('Неверный формат ответа сервера');
            }
        } catch (err) {
            console.error('Ошибка при загрузке кандидатов:', err);
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    // Функция для форматирования даты и времени
    const formatDateTime = (dateString) => {
        if (!dateString) return 'Не указано';
        
        try {
            const date = new Date(dateString);
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            
            return `${day}.${month}.${year} ${hours}:${minutes}`;
        } catch (err) {
            return 'Неверная дата';
        }
    };

    // Функция для получения ID статуса
    const getStatusId = (status) => {
        switch (status) {
            case 'Новая анкета':
                return 'new';
            case 'Проверен':
                return 'checked';
            case 'Нужна доработка':
                return 'needRevision';
            case 'Отклонен':
                return 'rejected';
            default:
                return 'unknown';
        }
    };

    // Функция для обработки клика по строке
    const handleRowClick = (candidate, event) => {
        // Проверяем, что клик не был по checkbox или кнопкам
        if (event.target.type === 'checkbox' || 
            event.target.closest('button') || 
            event.target.closest('label')) {
            return;
        }
        
        // Передаем vacancyKey вместо всего объекта candidate
        if (onRowClick) {
            onRowClick(candidate.vacancyKey);
        }
    };

    // Функция для обработки смены страницы
    const handlePageChange = (page) => {
        if (page >= 1 && page <= pagination.last_page && page !== pagination.current_page) {
            fetchCandidates(page);
        }
    };

    // Функция для генерации номеров страниц для пагинации
    const generatePageNumbers = () => {
        const { current_page, last_page } = pagination;
        const pages = [];
        
        if (last_page <= 5) {
            for (let i = 1; i <= last_page; i++) {
                pages.push(i);
            }
        } else {
            if (current_page <= 3) {
                pages.push(1, 2, 3, '...', last_page);
            } else if (current_page >= last_page - 2) {
                pages.push(1, '...', last_page - 2, last_page - 1, last_page);
            } else {
                pages.push(1, '...', current_page - 1, current_page, current_page + 1, '...', last_page);
            }
        }
        
        return pages;
    };

    // Загружаем данные при монтировании компонента
    useEffect(() => {
        fetchCandidates();
    }, []);

    // Для отладки - логируем изменения выбранных ключей
    useEffect(() => {
        console.log('Выбранные ключи:', selectedKeys);
    }, [selectedKeys]);

    const handleFormatDropdownToggle = (e) => {
        e.stopPropagation();
        setIsFormatDropdownOpen(!isFormatDropdownOpen);
    };

    const handleFormatSelect = (format) => {
        setSelectedFormat(format);
        setIsFormatDropdownOpen(false);
    };

    useEffect(() => {
        const handleClickOutside = (e) => {
            if (isFormatDropdownOpen && !e.target.closest('.download-button-group')) {
                setIsFormatDropdownOpen(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, [isFormatDropdownOpen]);

    // Показываем загрузку
    if (loading) {
        return (
            <section style={{flexWrap: 'wrap', minHeight: 'auto'}}>
                <div className="formRow justify-space-between w-80">
                    <div className="flex-direction-column">
                        <h1>Кандидаты</h1>
                        <p>Загрузка данных...</p>
                    </div>
                </div>
            </section>
        );
    }

    // Показываем ошибку
    if (error) {
        return (
            <section style={{flexWrap: 'wrap', minHeight: 'auto'}}>
                <div className="formRow justify-space-between w-80">
                    <div className="flex-direction-column">
                        <h1>Кандидаты</h1>
                        <div style={{
                            color: '#d32f2f',
                            fontSize: '14px',
                            marginTop: '10px',
                            padding: '8px',
                            backgroundColor: '#ffebee',
                            border: '1px solid #ffcdd2',
                            borderRadius: '4px'
                        }}>
                            Ошибка: {error}
                        </div>
                        <button 
                            className="aButton" 
                            onClick={() => fetchCandidates()}
                            style={{marginTop: '10px'}}
                        >
                            Повторить попытку
                        </button>
                    </div>
                </div>
            </section>
        );
    }

    return (
        <section style={{flexWrap: 'wrap', minHeight: 'auto'}}>
            <div className="formRow justify-space-between w-80">
                <div className="flex-direction-column">
                    <h1>Кандидаты</h1>
                    <button className="aButton" id="checkAll" onClick={handleSelectAll}>
                        {candidates.length > 0 && candidates.every(c => selectedKeys.includes(c.vacancyKey)) 
                            ? 'Снять выбор со всех' 
                            : 'Выбрать всех'}
                    </button>
                </div>
                <button 
                    ref={filtersButtonRef}
                    id="filters" 
                    aria-label="Нажмите, чтобы открыть фильтры"
                    onClick={onFiltersClick}
                >
                    <img src="img/filters.png" alt="PNG картинка, фильтров" />
                    Фильтры
                </button>
            </div>
            
            {candidates.length === 0 ? (
                <div className="w-80" style={{textAlign: 'center', padding: '40px'}}>
                    <p>Нет данных для отображения</p>
                </div>
            ) : (
                <>
                    <table className="candidatesTable w-80">
                        <thead>
                            <tr style={{border: '0'}}>
                                <th width="50"></th>
                                <th>ФИО Кандидата</th>
                                <th>Дата и время</th>
                                <th>Вакансия</th>
                                <th style={{textAlign: 'right', paddingRight: '30px'}}>Статус</th>
                                <th width="100"></th>
                            </tr>
                        </thead>
                        <tbody id="candidatesTableBody">
                            {candidates.map((candidate) => (
                                <tr 
                                    key={candidate.id} 
                                    data-keyvacancy={candidate.vacancyKey}
                                    onClick={(e) => handleRowClick(candidate, e)}
                                    style={{cursor: 'pointer'}}
                                >
                                    <td>
                                        <label className="custom-checkbox" htmlFor={`personalData${candidate.id}`}>
                                            <input 
                                                type="checkbox" 
                                                name="personalData" 
                                                id={`personalData${candidate.id}`}
                                                checked={selectedKeys.includes(candidate.vacancyKey)}
                                                onChange={(e) => handleCheckboxChange(candidate.vacancyKey, e.target.checked)}
                                            />
                                            <span className="checkmark"></span>
                                        </label>
                                    </td>
                                    <td>{candidate.name}</td>
                                    <td>{candidate.datetime}</td>
                                    <td>{candidate.vacancy}</td>
                                    <td style={{display: 'flex', justifyContent: 'flex-end', marginRight: '20px'}}>
                                        <p id={candidate.statusID}>{candidate.status}</p>
                                    </td>
                                    <td>
                                        {candidate.hasVacancyComment && (
                                            <button 
                                                id={`radactBtn${candidate.id}`}
                                                onClick={(e) => e.stopPropagation()}
                                                title = {candidate.hasVacancyComment }
                                            >
                                                <img src="img/pen.png" alt="Редактировать анкету" />
                                            </button>
                                        )}
                                        <button 
                                            id={`downloadBtn${candidate.id}`}
                                            onClick={(e) => e.stopPropagation()}
                                        >
                                            <img src="img/download.png" alt="Скачать анкету" />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                    
                    <div className="formRow w-80 justify-space-between" style={{marginTop: '2rem'}}>
                        <div className="left-side">
                            <button 
                                id="prevBtn" 
                                className={`navBtn ${pagination.current_page === 1 ? 'inactive' : ''}`}
                                onClick={() => handlePageChange(pagination.current_page - 1)}
                                disabled={pagination.current_page === 1}
                            >
                                Предыдущая
                            </button>
                            <div className="pagination">
                                {generatePageNumbers().map((page, index) => (
                                    <button 
                                        key={index}
                                        className={`paginationBtn ${page === pagination.current_page ? 'active' : ''}`}
                                        onClick={() => typeof page === 'number' ? handlePageChange(page) : null}
                                        disabled={typeof page !== 'number'}
                                    >
                                        {page}
                                    </button>
                                ))}
                            </div>
                            <button 
                                id="nexBtn" 
                                className={`navBtn ${pagination.current_page === pagination.last_page ? 'inactive' : ''}`}
                                onClick={() => handlePageChange(pagination.current_page + 1)}
                                disabled={pagination.current_page === pagination.last_page}
                            >
                                Следующая
                            </button>
                        </div>
                        <div className="download-button-group right-side">
                            <button 
                                className="download-btn primary"
                                onClick={handleDownload}
                                disabled={downloadLoading}
                            >
                                {downloadLoading ? 'Скачивание...' : 'Скачать'}
                            </button>
                            <button 
                                className="download-btn dropdown-toggle"
                                onClick={handleFormatDropdownToggle}
                                disabled={downloadLoading}
                            >
                                <span className="format-text">{selectedFormat}</span>
                                <svg className="chevron-down" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                    <path d="M6 9L12 15L18 9" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                </svg>
                            </button>
                            <div className={`file-formats-card ${isFormatDropdownOpen ? '' : 'hide'}`}>
                                <div className="format-item" onClick={() => handleFormatSelect('.xlsx')}>.xlsx</div>
                                <div className="format-item" onClick={() => handleFormatSelect('.pdf')}>.pdf</div>
                            </div>
                        </div>
                    </div>
                </>
            )}
        </section>
    );
}

        // FiltersCalendar Component
function FiltersCalendar({ isOpen, onClose, filtersButtonRef }) {
    const [selectedFilters, setSelectedFilters] = useState({
        status: [],
        vacancy: [],
        dateRange: {
            type: 'dates',
            start: null,
            end: null
        }
    });
    
    const [startDate, setStartDate] = useState(null);
    const [endDate, setEndDate] = useState(null);
    const [currentRangeType, setCurrentRangeType] = useState('dates');
    const [calendar1Date, setCalendar1Date] = useState(new Date(2022, 8, 1));
    const [calendar2Date, setCalendar2Date] = useState(new Date(2024, 8, 1));
    const [isCustomSelectOpen, setIsCustomSelectOpen] = useState(false);

    // Новые состояния для загрузки данных вакансий
    const [vacancyOptions, setVacancyOptions] = useState([]);
    const [isLoadingVacancies, setIsLoadingVacancies] = useState(true);
    const [vacancyError, setVacancyError] = useState('');

    const monthNames = [
        'Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь',
        'Июль', 'Авг', 'Сент', 'Окт', 'Нояб', 'Дек'
    ];

    const [isLoadingCandidates, setIsLoadingCandidates] = useState(false);
    const [candidatesError, setCandidatesError] = useState('');
    const [candidatesData, setCandidatesData] = useState(null);

    // Исправленная функция для форматирования параметров запроса
    const formatApiDateRange = (startDate, endDate, type) => {
        if (!startDate || !endDate) return null;
        
        const formatDate = (date, rangeType) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            
            switch (rangeType) {
                case 'years':
                    return year.toString();
                case 'months':
                    return `${month}.${year}`;
                case 'dates':
                    return `${day}.${month}.${year}`;
                default:
                    return '';
            }
        };
        
        const start = formatDate(startDate, type);
        const end = formatDate(endDate, type);
        
        return `${start},${end}`;
    };

    // Функция для получения названий статусов для API
    const getStatusApiValues = (statusValues) => {
        const statusMapping = {
            'showAll': null, // Не передаем в API
            'newForm': 'Новая анкета',
            'checked': 'Проверено', 
            'needRevision': 'Нужна доработка',
            'rejected': 'Отклонен'
        };
        
        return statusValues
            .filter(status => status !== 'showAll' && statusMapping[status])
            .map(status => statusMapping[status]);
    };

    // Исправленная функция для получения названий вакансий для API
    const getVacancyApiValues = (vacancyValues) => {
        if (vacancyValues.includes('showAll')) {
            return []; // Если выбран "Показать все", не передаем параметр
        }
        
        return vacancyValues
            .map(vacancyId => {
                const vacancy = vacancyOptions.find(option => option.value === vacancyId);
                return vacancy ? vacancy.title : null; // Используем title вместо text
            })
            .filter(Boolean);
    };

    const calendarPanelRef = useRef(null);

    // Статус фильтры остаются хардкодом (так как это состояния заявок)
    const statusFilters = [
        {value: 'showAll', text: 'Показать все'},
        {value: 'newForm', text: 'Новая анкета'},
        {value: 'checked', text: 'Проверен'},
        {value: 'needRevision', text: 'Нужна доработка'},
        {value: 'rejected', text: 'Отклонен'}
    ];

    // Функция для получения токена из cookie
    const getAccessTokenFromCookie = () => {
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'access_token') {
                return value;
            }
        }
        return null;
    };

    // Исправленная функция для загрузки вакансий из API
    const loadVacancies = async () => {
        try {
            setIsLoadingVacancies(true);
            setVacancyError('');

            const accessToken = getAccessTokenFromCookie();
            
            if (!accessToken) {
                setVacancyError('Токен доступа не найден');
                return;
            }

            const response = await fetch('/api/v1/vacancy/', {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${accessToken}` 
                }
            });

            const data = await response.json();

            if (data.response && data.attributes) {
                // Формируем массив для фильтров вакансий с сохранением title
                const vacancies = [
                    {value: 'showAll', text: 'Показать все', title: null},
                    ...data.attributes.map(vacancy => ({
                        value: vacancy.id.toString(), // используем ID как value
                        text: vacancy.title, // для отображения
                        title: vacancy.title // для API запросов
                    }))
                ];
                setVacancyOptions(vacancies);
                console.log('Вакансии загружены для фильтров:', vacancies);
            } else {
                setVacancyError('Ошибка при получении данных вакансий');
            }
        } catch (error) {
            console.error('Ошибка при загрузке вакансий:', error);
            
            if (error.response) {
                if (error.response.status === 401) {
                    setVacancyError('Ошибка авторизации. Пожалуйста, войдите в систему заново.');
                } else if (error.response.status === 403) {
                    setVacancyError('Нет доступа к данным вакансий');
                } else {
                    setVacancyError(error.response.data?.error || 'Ошибка сервера при загрузке вакансий');
                }
            } else {
                setVacancyError('Ошибка при загрузке вакансий');
            }
        } finally {
            setIsLoadingVacancies(false);
        }
    };

    // Загружаем вакансии при монтировании компонента
    useEffect(() => {
        loadVacancies();
    }, []);

    const handleCustomSelectToggle = (e) => {
        e.stopPropagation();
        setIsCustomSelectOpen(!isCustomSelectOpen);
    };

    const handleRangeTypeSelect = (type) => {
        setCurrentRangeType(type);
        setStartDate(null);
        setEndDate(null);
        setIsCustomSelectOpen(false);
    };

    const handleFilterToggle = (filter, value) => {
        setSelectedFilters(prev => {
            const newFilters = { ...prev };
            if (filter === 'status' || filter === 'vacancy') {
                if (newFilters[filter].includes(value)) {
                    newFilters[filter] = newFilters[filter].filter(v => v !== value);
                } else {
                    newFilters[filter] = [...newFilters[filter], value];
                }
            }
            return newFilters;
        });
    };

    const handleCalendarNavigation = (calendar, direction) => {
        if (calendar === 1) {
            const newDate = new Date(calendar1Date);
            if (currentRangeType === 'dates') {
                newDate.setMonth(newDate.getMonth() + direction);
            } else if (currentRangeType === 'months') {
                newDate.setFullYear(newDate.getFullYear() + direction);
            }
            setCalendar1Date(newDate);
        } else {
            const newDate = new Date(calendar2Date);
            if (currentRangeType === 'dates') {
                newDate.setMonth(newDate.getMonth() + direction);
            } else if (currentRangeType === 'months') {
                newDate.setFullYear(newDate.getFullYear() + direction);
            }
            setCalendar2Date(newDate);
        }
    };

    const handleDateClick = (dateStr, year, month, day) => {
        const selectedDate = new Date(dateStr);
        
        if (currentRangeType === 'dates') {
            if (startDate && startDate.getTime() === selectedDate.getTime()) {
                setStartDate(null);
                setEndDate(null);
            } else if (!startDate || (startDate && endDate)) {
                setStartDate(selectedDate);
                setEndDate(null);
            } else if (selectedDate < startDate) {
                setEndDate(startDate);
                setStartDate(selectedDate);
            } else {
                setEndDate(selectedDate);
            }
        } else if (currentRangeType === 'months') {
            const selectedMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
            if (startDate && startDate.getTime() === selectedMonth.getTime()) {
                setStartDate(null);
                setEndDate(null);
            } else if (!startDate || (startDate && endDate)) {
                setStartDate(selectedMonth);
                setEndDate(null);
            } else if (selectedMonth < startDate) {
                setEndDate(startDate);
                setStartDate(selectedMonth);
            } else {
                setEndDate(selectedMonth);
            }
        } else if (currentRangeType === 'years') {
            const selectedYear = new Date(selectedDate.getFullYear(), 0, 1);
            if (startDate && startDate.getTime() === selectedYear.getTime()) {
                setStartDate(null);
                setEndDate(null);
            } else if (!startDate || (startDate && endDate)) {
                setStartDate(selectedYear);
                setEndDate(null);
            } else if (selectedYear < startDate) {
                setEndDate(startDate);
                setStartDate(selectedYear);
            } else {
                setEndDate(selectedYear);
            }
        }
    };

    // Исправленная функция handleApplyFilters
    const handleApplyFilters = async () => {
        try {
            setIsLoadingCandidates(true);
            setCandidatesError('');
            
            const updatedFilters = {
                ...selectedFilters,
                dateRange: {
                    type: currentRangeType,
                    start: startDate ? new Date(startDate) : null,
                    end: endDate ? new Date(endDate) : null
                }
            };

            // Формируем параметры для API запроса
            const queryParams = [];
            
            // Добавляем диапазон дат в зависимости от типа
            if (updatedFilters.dateRange.start && updatedFilters.dateRange.end) {
                const dateRange = formatApiDateRange(
                    updatedFilters.dateRange.start, 
                    updatedFilters.dateRange.end, 
                    updatedFilters.dateRange.type
                );
                
                if (dateRange) {
                    switch (updatedFilters.dateRange.type) {
                        case 'years':
                            queryParams.push(`year_range=${dateRange}`);
                            break;
                        case 'months':
                            queryParams.push(`month_range=${dateRange}`);
                            break;
                        case 'dates':
                            queryParams.push(`date_range=${dateRange}`);
                            break;
                    }
                }
            }
            
            // Добавляем статусы кандидатов
            const statusValues = getStatusApiValues(updatedFilters.status);
            if (statusValues.length > 0) {
                queryParams.push(`candidate_statuses=${statusValues.join(',')}`);
            }
            
            // Добавляем названия вакансий
            const vacancyValues = getVacancyApiValues(updatedFilters.vacancy);
            if (vacancyValues.length > 0) {
                queryParams.push(`vacancy_title=${vacancyValues.join(',')}`);
            }

            // Формируем query string с правильными разделителями
            const queryString = queryParams.join('&');
            
            // Логируем параметры запроса
            console.log('=== ФИЛЬТРЫ КАЛЕНДАРЯ ===');
            console.log('Применяемые фильтры:', {
                dateRange: updatedFilters.dateRange,
                status: updatedFilters.status,
                vacancy: updatedFilters.vacancy
            });
            console.log('Параметры API запроса:', queryString);
            console.log('Полный URL запроса:', `/api/v1/candidate-profiles${queryString ? '?' + queryString : ''}`);

            // Получаем токен доступа
            const accessToken = getAccessTokenFromCookie();
            
            if (!accessToken) {
                throw new Error('Токен доступа не найден');
            }

            // Выполняем API запрос
            const apiUrl = `/api/v1/candidate-profiles${queryString ? '?' + queryString : ''}`;
            console.log('Отправляем запрос на:', apiUrl);
            
            const response = await fetch(apiUrl, {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${accessToken}` 
                }
            });

            console.log('Статус ответа:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Полученные данные кандидатов:', data);
            
            // Сохраняем данные
            setCandidatesData(data);
            
            // Здесь можно передать данные в родительский компонент или CandidatesTable
            // Например, через пропс onFiltersApply:
            // if (onFiltersApply) {
            //     onFiltersApply(data, updatedFilters);
            // }
            
            console.log('Фильтры успешно применены, данные загружены');
            onClose();
            
        } catch (error) {
            console.error('Ошибка при применении фильтров:', error);
            
            let errorMessage = 'Ошибка при загрузке данных кандидатов';
            
            if (error.message.includes('404')) {
                errorMessage = 'API endpoint не найден';
            } else if (error.message.includes('401')) {
                errorMessage = 'Ошибка авторизации. Пожалуйста, войдите в систему заново.';
            } else if (error.message.includes('403')) {
                errorMessage = 'Нет доступа к данным кандидатов';
            } else if (error.message.includes('500')) {
                errorMessage = 'Ошибка сервера';
            } else if (error.message === 'Токен доступа не найден') {
                errorMessage = error.message;
            }
            
            setCandidatesError(errorMessage);
            console.log('Ошибка обработана:', errorMessage);
            
        } finally {
            setIsLoadingCandidates(false);
        }
    };

    const formatDateForUrl = (date, type) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        if (type === 'dates') return `${year}-${month}-${day}`;
        if (type === 'months') return `${year}-${month}`;
        if (type === 'years') return `${year}`;
        return '';
    };

    const generateCalendar = (year, month) => {
        const firstDay = new Date(year, month, 1).getDay();
        const startDayIndex = firstDay === 0 ? 6 : firstDay - 1;
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const calendar = [];
        let date = 1;

        for (let i = 0; i < 6; i++) {
            const week = [];
            let isRowEmpty = true;
            
            for (let j = 0; j < 7; j++) {
                if ((i === 0 && j < startDayIndex) || date > daysInMonth) {
                    week.push(null);
                } else {
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                    week.push({
                        day: date,
                        dateStr,
                        year,
                        month,
                        isSelected: isDateSelected(dateStr),
                        isInRange: isDateInRange(dateStr),
                        isStartDate: isStartDate(dateStr),
                        isEndDate: isEndDate(dateStr)
                    });
                    date++;
                    isRowEmpty = false;
                }
            }
            
            if (!isRowEmpty || i === 0) {
                calendar.push(week);
            }
            if (date > daysInMonth) break;
        }
        
        return calendar;
    };

    const generateMonthsCalendar = (year) => {
        const months = [];
        for (let i = 0; i < 3; i++) {
            const row = [];
            for (let j = 0; j < 4; j++) {
                const monthIndex = i * 4 + j;
                if (monthIndex >= 12) break;
                const dateStr = `${year}-${String(monthIndex + 1).padStart(2, '0')}-01`;
                row.push({
                    month: monthIndex,
                    name: monthNames[monthIndex],
                    dateStr,
                    year,
                    isSelected: isMonthSelected(year, monthIndex),
                    isInRange: isMonthInRange(year, monthIndex),
                    isStartDate: isMonthStartDate(year, monthIndex),
                    isEndDate: isMonthEndDate(year, monthIndex)
                });
            }
            months.push(row);
        }
        return months;
    };

    const generateYearsCalendar = () => {
        const startYear = 2020;
        const endYear = 2025;
        const years = [];
        
        for (let i = 0; i < Math.ceil((endYear - startYear + 1) / 3); i++) {
            const row = [];
            for (let j = 0; j < 3; j++) {
                const yearIndex = startYear + i * 3 + j;
                if (yearIndex > endYear) break;
                const dateStr = `${yearIndex}-01-01`;
                row.push({
                    year: yearIndex,
                    dateStr,
                    isSelected: isYearSelected(yearIndex),
                    isInRange: isYearInRange(yearIndex),
                    isStartDate: isYearStartDate(yearIndex),
                    isEndDate: isYearEndDate(yearIndex)
                });
            }
            years.push(row);
        }
        return years;
    };

    const isDateSelected = (dateStr) => {
        if (!startDate) return false;
        const date = new Date(dateStr);
        return startDate.getTime() === date.getTime() || (endDate && endDate.getTime() === date.getTime());
    };

    const isDateInRange = (dateStr) => {
        if (!startDate || !endDate) return false;
        const date = new Date(dateStr);
        return date > startDate && date < endDate;
    };

    const isStartDate = (dateStr) => {
        if (!startDate) return false;
        const date = new Date(dateStr);
        return startDate.getTime() === date.getTime();
    };

    const isEndDate = (dateStr) => {
        if (!startDate || !endDate) return false;
        const date = new Date(dateStr);
        return endDate.getTime() === date.getTime();
    };

    // Функции для проверки состояния месяцев
    const isMonthSelected = (year, monthIndex) => {
        if (!startDate) return false;
        const monthDate = new Date(year, monthIndex, 1);
        return (startDate && startDate.getTime() === monthDate.getTime()) || 
               (endDate && endDate.getTime() === monthDate.getTime());
    };

    const isMonthInRange = (year, monthIndex) => {
        if (!startDate || !endDate) return false;
        const monthDate = new Date(year, monthIndex, 1);
        return monthDate > startDate && monthDate < endDate;
    };

    const isMonthStartDate = (year, monthIndex) => {
        if (!startDate) return false;
        const monthDate = new Date(year, monthIndex, 1);
        return startDate.getTime() === monthDate.getTime();
    };

    const isMonthEndDate = (year, monthIndex) => {
        if (!startDate || !endDate) return false;
        const monthDate = new Date(year, monthIndex, 1);
        return endDate.getTime() === monthDate.getTime();
    };

    // Функции для проверки состояния годов
    const isYearSelected = (year) => {
        if (!startDate) return false;
        const yearDate = new Date(year, 0, 1);
        return (startDate && startDate.getTime() === yearDate.getTime()) || 
               (endDate && endDate.getTime() === yearDate.getTime());
    };

    const isYearInRange = (year) => {
        if (!startDate || !endDate) return false;
        const yearDate = new Date(year, 0, 1);
        return yearDate > startDate && yearDate < endDate;
    };

    const isYearStartDate = (year) => {
        if (!startDate) return false;
        const yearDate = new Date(year, 0, 1);
        return startDate.getTime() === yearDate.getTime();
    };

    const isYearEndDate = (year) => {
        if (!startDate || !endDate) return false;
        const yearDate = new Date(year, 0, 1);
        return endDate.getTime() === yearDate.getTime();
    };

    useEffect(() => {
        const handleClickOutside = (e) => {
            if (isCustomSelectOpen && !e.target.closest('.custom-select')) {
                setIsCustomSelectOpen(false);
            }
            if (isOpen && calendarPanelRef.current && !calendarPanelRef.current.contains(e.target) && filtersButtonRef.current && !filtersButtonRef.current.contains(e.target)) {
                onClose();
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, [isCustomSelectOpen, isOpen, onClose]);

    useEffect(() => {
        if (isOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
        
        return () => {
            document.body.style.overflow = '';
        };
    }, [isOpen]);

 return (
        <>
            <aside 
                className={`calendar-filter-panel ${isOpen ? 'open' : ''}`} 
                id="calendarPanel"
                ref={calendarPanelRef}
            >
                <div className="center-card" style={{minWidth: '800px', height: '100%', paddingBottom: '50px'}}>
                    <div className="formRow flex-direction-column" style={{marginTop: '20px'}}>
                        <div className="custom-select">
                            <div 
                                className={`select-selected ${isCustomSelectOpen ? 'select-arrow-active' : ''}`}
                                onClick={handleCustomSelectToggle}
                            >
                                {currentRangeType === 'dates' ? 'Диапазон дат' : 
                                 currentRangeType === 'months' ? 'Диапазон месяцев' : 'Диапазон годов'}
                            </div>
                            <div className={`select-items ${isCustomSelectOpen ? '' : 'select-hide'}`}>
                                <div 
                                    className={currentRangeType === 'dates' ? 'same-as-selected' : ''}
                                    onClick={() => handleRangeTypeSelect('dates')}
                                >
                                    Диапазон дат
                                </div>
                                <div 
                                    className={currentRangeType === 'months' ? 'same-as-selected' : ''}
                                    onClick={() => handleRangeTypeSelect('months')}
                                >
                                    Диапазон месяцев
                                </div>
                                <div 
                                    className={currentRangeType === 'years' ? 'same-as-selected' : ''}
                                    onClick={() => handleRangeTypeSelect('years')}
                                >
                                    Диапазон годов
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div className="calendar-container">
                        <div className="calendar-wrapper">
                            <div className="calendar-header">
                                <span 
                                    className="nav-arrow" 
                                    style={{display: currentRangeType === 'years' ? 'none' : 'inline'}}
                                    onClick={() => handleCalendarNavigation(1, -1)}
                                >
                                    &#8249;
                                </span>
                                <span className="month-year">
                                    {currentRangeType === 'dates' ? 
                                        `${monthNames[calendar1Date.getMonth()]} ${calendar1Date.getFullYear()}` :
                                     currentRangeType === 'months' ? 
                                        calendar1Date.getFullYear() : 'ОТ'}
                                </span>
                                <span 
                                    className="nav-arrow" 
                                    style={{display: currentRangeType === 'years' ? 'none' : 'inline'}}
                                    onClick={() => handleCalendarNavigation(1, 1)}
                                >
                                    &#8250;
                                </span>
                            </div>
                            <table className="calendar">
                                <thead style={{display: currentRangeType === 'dates' ? '' : 'none'}}>
                                    <tr>
                                        <th>Пн</th>
                                        <th>Вт</th>
                                        <th>Ср</th>
                                        <th>Чт</th>
                                        <th>Пт</th>
                                        <th>Сб</th>
                                        <th>Вс</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {currentRangeType === 'dates' && 
                                        generateCalendar(calendar1Date.getFullYear(), calendar1Date.getMonth()).map((week, weekIndex) => (
                                            <tr key={weekIndex}>
                                                {week.map((day, dayIndex) => (
                                                    <td 
                                                        key={dayIndex}
                                                        className={day ? [
                                                            day.isInRange ? 'in-range' : '',
                                                            day.isStartDate ? 'start-date' : '',
                                                            day.isEndDate ? 'end-date' : '',
                                                            (day.isStartDate && endDate) ? 'start-date-bg' : '',
                                                            (day.isEndDate && startDate) ? 'end-date-bg' : ''
                                                        ].filter(Boolean).join(' ') : ''}
                                                        onClick={day ? () => handleDateClick(day.dateStr, day.year, day.month, day.day) : undefined}
                                                        style={{cursor: day ? 'pointer' : 'default', position: 'relative'}}
                                                    >
                                                        {day && <span className="day-number">{day.day}</span>}
                                                    </td>
                                                ))}
                                            </tr>
                                        ))
                                    }
                                    {currentRangeType === 'months' && 
                                        generateMonthsCalendar(calendar1Date.getFullYear()).map((row, rowIndex) => (
                                            <tr key={rowIndex}>
                                                {row.map((month, monthIndex) => (
                                                    <td 
                                                        key={monthIndex}
                                                        colSpan={monthIndex === 3 && rowIndex === 2 ? 3 : 2}
                                                        className={[
                                                            month.isInRange ? 'in-range' : '',
                                                            month.isStartDate ? 'start-date' : '',
                                                            month.isEndDate ? 'end-date' : '',
                                                            (month.isStartDate && endDate) ? 'start-date-bg' : '',
                                                            (month.isEndDate && startDate) ? 'end-date-bg' : ''
                                                        ].filter(Boolean).join(' ')}
                                                        onClick={() => handleDateClick(month.dateStr, month.year, month.month)}
                                                        style={{cursor: 'pointer', position: 'relative'}}
                                                    >
                                                        {month.name}
                                                    </td>
                                                ))}
                                            </tr>
                                        ))
                                    }
                                    {currentRangeType === 'years' && 
                                        generateYearsCalendar().map((row, rowIndex) => (
                                            <tr key={rowIndex}>
                                                {row.map((year, yearIndex) => (
                                                    <td 
                                                        key={yearIndex}
                                                        colSpan={yearIndex === 2 ? 1 : 3}
                                                        className={[
                                                            year.isInRange ? 'in-range' : '',
                                                            year.isStartDate ? 'start-date' : '',
                                                            year.isEndDate ? 'end-date' : '',
                                                            (year.isStartDate && endDate) ? 'start-date-bg' : '',
                                                            (year.isEndDate && startDate) ? 'end-date-bg' : ''
                                                        ].filter(Boolean).join(' ')}
                                                        onClick={() => handleDateClick(year.dateStr, year.year)}
                                                        style={{cursor: 'pointer', position: 'relative'}}
                                                    >
                                                        {year.year}
                                                    </td>
                                                ))}
                                            </tr>
                                        ))
                                    }
                                </tbody>
                            </table>
                        </div>
                        
                        <div className="calendar-wrapper">
                            <div className="calendar-header">
                                <span 
                                    className="nav-arrow" 
                                    style={{display: currentRangeType === 'years' ? 'none' : 'inline'}}
                                    onClick={() => handleCalendarNavigation(2, -1)}
                                >
                                    &#8249;
                                </span>
                                <span className="month-year">
                                    {currentRangeType === 'dates' ? 
                                        `${monthNames[calendar2Date.getMonth()]} ${calendar2Date.getFullYear()}` :
                                     currentRangeType === 'months' ? 
                                        calendar2Date.getFullYear() : 'ДО'}
                                </span>
                                <span 
                                    className="nav-arrow" 
                                    style={{display: currentRangeType === 'years' ? 'none' : 'inline'}}
                                    onClick={() => handleCalendarNavigation(2, 1)}
                                >
                                    &#8250;
                                </span>
                            </div>
                            <table className="calendar">
                                <thead style={{display: currentRangeType === 'dates' ? '' : 'none'}}>
                                    <tr>
                                        <th>Пн</th>
                                        <th>Вт</th>
                                        <th>Ср</th>
                                        <th>Чт</th>
                                        <th>Пт</th>
                                        <th>Сб</th>
                                        <th>Вс</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {currentRangeType === 'dates' && 
                                        generateCalendar(calendar2Date.getFullYear(), calendar2Date.getMonth()).map((week, weekIndex) => (
                                            <tr key={weekIndex}>
                                                {week.map((day, dayIndex) => (
                                                    <td 
                                                        key={dayIndex}
                                                        className={day ? [
                                                            day.isInRange ? 'in-range' : '',
                                                            day.isStartDate ? 'start-date' : '',
                                                            day.isEndDate ? 'end-date' : '',
                                                            (day.isStartDate && endDate) ? 'start-date-bg' : '',
                                                            (day.isEndDate && startDate) ? 'end-date-bg' : ''
                                                        ].filter(Boolean).join(' ') : ''}
                                                        onClick={day ? () => handleDateClick(day.dateStr, day.year, day.month, day.day) : undefined}
                                                        style={{cursor: day ? 'pointer' : 'default', position: 'relative'}}
                                                    >
                                                        {day && <span className="day-number">{day.day}</span>}
                                                    </td>
                                                ))}
                                            </tr>
                                        ))
                                    }
                                    {currentRangeType === 'months' && 
                                        generateMonthsCalendar(calendar2Date.getFullYear()).map((row, rowIndex) => (
                                            <tr key={rowIndex}>
                                                {row.map((month, monthIndex) => (
                                                    <td 
                                                        key={monthIndex}
                                                        colSpan={monthIndex === 3 && rowIndex === 2 ? 3 : 2}
                                                        className={[
                                                            month.isInRange ? 'in-range' : '',
                                                            month.isStartDate ? 'start-date' : '',
                                                            month.isEndDate ? 'end-date' : '',
                                                            (month.isStartDate && endDate) ? 'start-date-bg' : '',
                                                            (month.isEndDate && startDate) ? 'end-date-bg' : ''
                                                        ].filter(Boolean).join(' ')}
                                                        onClick={() => handleDateClick(month.dateStr, month.year, month.month)}
                                                        style={{cursor: 'pointer', position: 'relative'}}
                                                    >
                                                        {month.name}
                                                    </td>
                                                ))}
                                            </tr>
                                        ))
                                    }
                                    {currentRangeType === 'years' && 
                                        generateYearsCalendar().map((row, rowIndex) => (
                                            <tr key={rowIndex}>
                                                {row.map((year, yearIndex) => (
                                                    <td 
                                                        key={yearIndex}
                                                        colSpan={yearIndex === 2 ? 1 : 3}
                                                        className={[
                                                            year.isInRange ? 'in-range' : '',
                                                            year.isStartDate ? 'start-date' : '',
                                                            year.isEndDate ? 'end-date' : '',
                                                            (year.isStartDate && endDate) ? 'start-date-bg' : '',
                                                            (year.isEndDate && startDate) ? 'end-date-bg' : ''
                                                        ].filter(Boolean).join(' ')}
                                                        onClick={() => handleDateClick(year.dateStr, year.year)}
                                                        style={{cursor: 'pointer', position: 'relative'}}
                                                    >
                                                        {year.year}
                                                    </td>
                                                ))}
                                            </tr>
                                        ))
                                    }
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div className="formRow">
                        <h3 style={{textAlign: 'left', paddingLeft: '10px'}}>Фильтр по статусу</h3>
                    </div>
                    <div className="formRow justify-flex-start" style={{paddingLeft: '10px', flexWrap: 'wrap'}}>
                        {statusFilters.map(filter => (
                            <button 
                                key={filter.value}
                                className={`filterButton ${selectedFilters.status.includes(filter.value) ? 'active' : ''}`}
                                onClick={() => handleFilterToggle('status', filter.value)}
                                disabled={isLoadingCandidates}
                            >
                                {filter.text}
                            </button>
                        ))}
                    </div>
                    
                    <div className="formRow">
                        <h3 style={{textAlign: 'left', paddingLeft: '10px'}}>Фильтр по вакансии</h3>
                    </div>
                    <div className="formRow justify-flex-start" style={{paddingLeft: '10px', flexWrap: 'wrap'}}>
                        {/* Показываем индикатор загрузки или ошибку */}
                        {isLoadingVacancies && (
                            <div style={{padding: '10px', color: '#666', fontSize: '14px'}}>
                                <div style={{
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '8px'
                                }}>
                                    <div style={{
                                        width: '16px',
                                        height: '16px',
                                        border: '2px solid #f3f3f3',
                                        borderTop: '2px solid #EC7D3F',
                                        borderRadius: '50%',
                                        animation: 'spin 1s linear infinite'
                                    }}></div>
                                    Загрузка вакансий...
                                </div>
                            </div>
                        )}
                        {vacancyError && (
                            <div style={{
                                padding: '10px',
                                backgroundColor: '#ffeaea',
                                border: '1px solid #e74c3c',
                                borderRadius: '4px',
                                color: '#c0392b',
                                fontSize: '14px',
                                maxWidth: '100%'
                            }}>
                                {vacancyError}
                                <button
                                    onClick={loadVacancies}
                                    style={{
                                        marginLeft: '10px',
                                        background: 'none',
                                        border: 'none',
                                        color: '#3498db',
                                        cursor: 'pointer',
                                        textDecoration: 'underline',
                                        fontSize: '14px'
                                    }}
                                >
                                    Повторить
                                </button>
                            </div>
                        )}
                        {/* Отображаем кнопки фильтров, загруженные из API */}
                        {!isLoadingVacancies && !vacancyError && vacancyOptions.map((filter) => (
                            <button 
                                key={filter.value}
                                className={`filterButton ${selectedFilters.vacancy.includes(filter.value) ? 'active' : ''}`}
                                onClick={() => handleFilterToggle('vacancy', filter.value)}
                                disabled={isLoadingCandidates}
                            >
                                {filter.text}
                            </button>
                        ))}
                    </div>

                    {/* Отображение ошибки применения фильтров */}
                    {candidatesError && (
                        <div className="formRow" style={{marginTop: '15px'}}>
                            <div style={{
                                padding: '15px',
                                backgroundColor: '#ffeaea',
                                border: '1px solid #e74c3c',
                                borderRadius: '6px',
                                color: '#c0392b',
                                fontSize: '14px',
                                lineHeight: '1.4'
                            }}>
                                <strong>Ошибка при загрузке данных:</strong><br />
                                {candidatesError}
                            </div>
                        </div>
                    )}

                    {/* Отображение успешного результата */}
                    {candidatesData && !candidatesError && (
                        <div className="formRow" style={{marginTop: '15px'}}>
                            <div style={{
                                padding: '15px',
                                backgroundColor: '#eafaf1',
                                border: '1px solid #27ae60',
                                borderRadius: '6px',
                                color: '#1e8449',
                                fontSize: '14px',
                                lineHeight: '1.4'
                            }}>
                                <strong>Фильтры успешно применены!</strong><br />
                                {candidatesData.attributes ? 
                                    `Найдено кандидатов: ${candidatesData.attributes.length}` : 
                                    'Данные загружены'
                                }
                            </div>
                        </div>
                    )}
                    
                    <div className="formRow justify-space-between" style={{marginTop: '25px'}}>
                        <button 
                            className={`formBtn ${isLoadingCandidates ? 'btn-inactive' : 'btn-active'}`}
                            onClick={handleApplyFilters}
                            disabled={isLoadingCandidates}
                            style={{
                                position: 'relative',
                                minWidth: '140px',
                                height: '45px'
                            }}
                        >
                            {isLoadingCandidates ? (
                                <>
                                    <span style={{opacity: 0}}>Применить</span>
                                    <div style={{
                                        position: 'absolute',
                                        top: '50%',
                                        left: '50%',
                                        transform: 'translate(-50%, -50%)',
                                        display: 'flex',
                                        alignItems: 'center',
                                        gap: '8px'
                                    }}>
                                        <div style={{
                                            width: '18px',
                                            height: '18px',
                                            border: '2px solid #f3f3f3',
                                            borderTop: '2px solid #EC7D3F',
                                            borderRadius: '50%',
                                            animation: 'spin 1s linear infinite'
                                        }}></div>
                                        <span style={{fontSize: '14px', color: '#666'}}>Загрузка...</span>
                                    </div>
                                </>
                            ) : (
                                'Применить'
                            )}
                        </button>
                        <button 
                            className="formBtn btn-inactive" 
                            onClick={() => {
                                setSelectedFilters({status: [], vacancy: [], dateRange: {type: 'dates', start: null, end: null}});
                                setStartDate(null);
                                setEndDate(null);
                                setCurrentRangeType('dates');
                                setCandidatesError(''); // Очищаем ошибку при сбросе
                                setCandidatesData(null); // Очищаем данные при сбросе
                            }}
                            disabled={isLoadingCandidates}
                            style={{
                                minWidth: '140px',
                                height: '45px'
                            }}
                        >
                            Сбросить
                        </button>
                    </div>
                </div>
            </aside>
            
            <style dangerouslySetInnerHTML={{
                __html: `
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                    
                    .filterButton:disabled {
                        opacity: 0.6;
                        cursor: not-allowed;
                    }
                    
                    .formBtn:disabled {
                        opacity: 0.6;
                        cursor: not-allowed;
                    }
                    
                    .calendar td {
                        position: relative;
                    }
                    
                    ${currentRangeType === 'dates' ? `
                        .calendar td.start-date::after,
                        .calendar td.end-date::after {
                            content: '';
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            width: 30px;
                            height: 30px;
                            background-color: #EC7D3F;
                            border-radius: 50%;
                            z-index: 0;
                        }
                    ` : `
                        .calendar td.start-date::after,
                        .calendar td.end-date::after {
                            content: '';
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            width: 62px;
                            height: 32px;
                            background-color: #EC7D3F;
                            border-radius: 16px;
                            z-index: -1;
                        }
                    `}
                    
                    .calendar td .day-number,
                    .calendar td {
                        position: relative;
                        z-index: 1;
                    }
                `
            }} />
        </>
    );
}
function ShowForm({ vacancyKey }) { // Добавляем prop vacancyKey
    const [isSelectOpen, setIsSelectOpen] = useState(false);
    const [selectedOption, setSelectedOption] = useState({
        value: 'new',
        text: 'Новая анкета'
    });
    const [commentValue, setCommentValue] = useState('');
    const [isUpdating, setIsUpdating] = useState(false); // Состояние для индикации загрузки

    const selectOptions = [
        { value: 'new', text: 'Новая анкета' },
        { value: 'needs-work', text: 'Нужна доработка' },
        { value: 'checked', text: 'Проверен' },
        { value: 'rejected', text: 'Отклонен' }
    ];

    // Функция для получения CSRF токена
    const getCsrfToken = () => {
        // Попробуем получить CSRF токен из meta тега
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            return metaTag.getAttribute('content');
        }
        
        // Попробуем получить из cookie
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'XSRF-TOKEN') {
                return decodeURIComponent(value);
            }
        }
        
        // Используем фиксированный токен как в примере cURL
        return 'Zva2RlvTSh5wTQogjJMfE8v5ObQoOSIcL40Xwc5d';
    };

    // Функция для преобразования значения статуса в точно такой же формат как в API
    const mapStatusForAPI = (statusValue) => {
        const statusMap = {
            'new': 'Новая анкета',
            'needs-work': 'Нужна доработка', // Убрал пробел в конце
            'checked': 'Проверен',
            'rejected': 'Отклонен'
        };
        return statusMap[statusValue] || statusValue;
    };

    // Функция для получения access token из cookies
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

    // Функция для отправки запроса обновления статуса
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

    // ПРАВИЛЬНАЯ подготовка данных - как в рабочем примере
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
        console.log('URL:', 'http://127.0.0.1:8000/api/v1/candidates/update');
        console.log('Метод:', 'POST');
        console.log('Заголовки:', headers);
        console.log('Тело запроса (JSON):', JSON.stringify(requestData, null, 2));
        
        console.log('=== ОТПРАВКА ЗАПРОСА ===');
        
        const response = await fetch('http://127.0.0.1:8000/api/v1/candidates/update', {
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
            
            // Попробуем распарсить как JSON
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
        // Проверяем, изменился ли статус
        if (selectedOption.value !== option.value) {
            // Отправляем запрос на обновление статуса
            const success = await updateCandidateStatus(option.value);
            
            if (success) {
                // Обновляем состояние только если запрос прошел успешно
                setSelectedOption(option);
                console.log('Статус изменен на:', option.text);
            } else {
                console.error('Не удалось обновить статус');
                // Можно добавить уведомление пользователю об ошибке
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

        // Подготавливаем данные для отправки комментария
        const requestData = {
            key: vacancyKey,
            status: "", // Пустой статус для комментария
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
            console.log('URL:', 'http://127.0.0.1:8000/api/v1/candidates/update');
            console.log('Метод:', 'POST');
            console.log('Заголовки:', headers);
            console.log('Тело запроса (JSON):', JSON.stringify(requestData, null, 2));
            
            const response = await fetch('http://127.0.0.1:8000/api/v1/candidates/update', {
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

    // Закрытие селектора при клике вне его
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
                        <button id="notifBtn"><img src="img/ring.png" alt="Уведомлений нет" /></button>
                        <button id="exitBtn">Выйти из ЛК <img src="img/arowRight.png" alt="Стрелочка вправо" /></button>
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

function CandidatesSettings() {
    // Состояния для загрузки данных вакансий
    const [roles, setRoles] = useState([]);
    const [isLoadingRoles, setIsLoadingRoles] = useState(true);
    const [rolesError, setRolesError] = useState('');
    
    const [isAdding, setIsAdding] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [newRole, setNewRole] = useState('');
    const [editingIndex, setEditingIndex] = useState(null);
    const [editingRole, setEditingRole] = useState('');

    // Функция для получения токена из cookie
    const getAccessTokenFromCookie = () => {
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'access_token') {
                return value;
            }
        }
        return null;
    };

    // Функция для загрузки вакансий из API
    const loadRoles = async () => {
        try {
            setIsLoadingRoles(true);
            setRolesError('');

            const accessToken = getAccessTokenFromCookie();
            
            if (!accessToken) {
                setRolesError('Токен доступа не найден');
                return;
            }

            const response = await fetch('/api/v1/vacancy/', {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${accessToken}` 
                }
            });

            const data = await response.json();

            if (data.response && data.attributes) {
                // Формируем массив ролей из вакансий с сохранением key
                const rolesFromApi = data.attributes.map(vacancy => ({
                    id: vacancy.id,
                    key: vacancy.key,
                    title: vacancy.title
                }));
                setRoles(rolesFromApi);
                console.log('Роли загружены:', rolesFromApi);
            } else {
                setRolesError('Ошибка при получении данных вакансий');
            }
        } catch (error) {
            console.error('Ошибка при загрузке ролей:', error);
            
            if (error.response) {
                if (error.response.status === 401) {
                    setRolesError('Ошибка авторизации. Пожалуйста, войдите в систему заново.');
                } else if (error.response.status === 403) {
                    setRolesError('Нет доступа к данным вакансий');
                } else {
                    setRolesError(error.response.data?.error || 'Ошибка сервера при загрузке ролей');
                }
            } else {
                setRolesError('Ошибка при загрузке ролей');
            }
        } finally {
            setIsLoadingRoles(false);
        }
    };

    // Загружаем роли при монтировании компонента
    useEffect(() => {
        loadRoles();
    }, []);

    const handleAddRole = () => {
        console.log('=== НАЖАТА КНОПКА ДОБАВИТЬ РОЛЬ ===');
        console.log('Текущие состояния:');
        console.log('- editingIndex:', editingIndex);
        console.log('- isAdding:', isAdding);
        console.log('- newRole:', newRole);
        console.log('- editingRole:', editingRole);
        
        if (editingIndex !== null) {
            console.log('→ Режим: ПОДТВЕРЖДЕНИЕ РЕДАКТИРОВАНИЯ');
            // Если редактируем - сохраняем отредактированную роль
            saveEditedRole();
        } else if (isAdding) {
            console.log('→ Режим: СОХРАНЕНИЕ НОВОЙ РОЛИ');
            console.log('→ Значение в инпуте:', `"${newRole}"`);
            console.log('→ После trim():', `"${newRole.trim()}"`);
            console.log('→ Условие newRole.trim():', newRole.trim() ? 'TRUE' : 'FALSE');
            // Второе нажатие - сохраняем новую роль
            if (newRole.trim()) {
                console.log('→ ВЫЗЫВАЕМ saveNewRole()');
                saveNewRole();
            } else {
                console.log('→ НЕ вызываем saveNewRole() - пустое значение');
            }
        } else {
            console.log('→ Режим: ПОКАЗАТЬ ИНПУТ ДЛЯ ДОБАВЛЕНИЯ');
            // Первое нажатие - показываем input для добавления
            setIsAdding(true);
            setIsEditing(false);
            setEditingIndex(null); // Сбрасываем редактирование
            console.log('→ Установлено isAdding = true');
        }
        console.log('=== КОНЕЦ ОБРАБОТКИ КНОПКИ ===');
    };

    const handleEditMode = () => {
        setIsEditing(!isEditing);
        setIsAdding(false);
        setEditingIndex(null); // Сбрасываем редактирование при переключении режима
        setEditingRole('');
    };

    const saveNewRole = async () => {
        if (newRole.trim()) {
            const newTitle = newRole.trim();
            
            console.log('=== НАЧАЛО ДОБАВЛЕНИЯ НОВОЙ РОЛИ ===');
            console.log('Название новой роли:', newTitle);
            
            // Сразу добавляем в UI
            const newRoleObj = {
                id: `temp_${Date.now()}`,
                key: `temp_key_${Date.now()}`,
                title: newTitle
            };
            setRoles([...roles, newRoleObj]);
            setNewRole('');
            setIsAdding(false);
            
            console.log('Новая роль добавлена в UI:', newRoleObj);
            
            // Отправляем POST запрос на сервер для создания роли
            const requestData = {
                title: newTitle
            };
            
            console.log('Данные для отправки:', requestData);
            console.log('JSON для отправки:', JSON.stringify(requestData, null, 2));
            
            try {
                const accessToken = getAccessTokenFromCookie();
                console.log('Токен доступа для создания:', accessToken ? 'найден' : 'НЕ найден');
                
                if (accessToken) {
                    console.log('Отправка POST запроса к /api/v1/vacancy/store');
                    console.log('Заголовки запроса:', {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${accessToken.substring(0, 10)}...`
                    });
                    
                    const response = await fetch('/api/v1/vacancy/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${accessToken}` 
                        },
                        body: JSON.stringify(requestData)
                    });
                    
                    console.log('Статус ответа при создании:', response.status);
                    console.log('Статус OK при создании:', response.ok);
                    console.log('Заголовки ответа при создании:', Object.fromEntries(response.headers.entries()));
                    
                    const data = await response.json();
                    console.log('Данные ответа при создании:', data);
                    
                    if (response.ok && data.response) {
                        console.log('✅ Создание роли выполнено успешно');
                        console.log('Полученные данные новой роли:', data);
                        
                        // Если сервер вернул данные новой роли, можно обновить временную роль
                        if (data.attributes) {
                            console.log('Обновление временной роли данными с сервера');
                            const updatedRoles = [...roles];
                            const lastIndex = updatedRoles.length - 1;
                            updatedRoles[lastIndex] = {
                                id: data.attributes.id,
                                key: data.attributes.key,
                                title: data.attributes.title
                            };
                            setRoles(updatedRoles);
                            console.log('Роль обновлена данными с сервера:', updatedRoles[lastIndex]);
                        }
                    } else {
                        console.error('❌ Ошибка при создании роли от сервера');
                        console.error('Response.ok:', response.ok);
                        console.error('Data.response:', data.response);
                        console.error('Данные ошибки:', data);
                    }
                } else {
                    console.error('❌ Нет токена доступа для создания роли');
                }
            } catch (error) {
                console.error('❌ ИСКЛЮЧЕНИЕ при создании роли:', error);
                console.error('Тип ошибки при создании:', error.constructor.name);
                console.error('Сообщение ошибки при создании:', error.message);
                console.error('Stack trace при создании:', error.stack);
                
                if (error.response) {
                    console.error('Ответ сервера при ошибке создания:', error.response);
                    console.error('Статус ошибки создания:', error.response.status);
                    console.error('Данные ошибки создания:', error.response.data);
                }
            }
            
            console.log('=== СОЗДАНИЕ РОЛИ ЗАВЕРШЕНО ===');
        }
    };

    const cancelAdd = () => {
        setNewRole('');
        setIsAdding(false);
    };

    const editRole = (index) => {
        // Устанавливаем индекс редактируемой роли и заполняем инпут текущим значением
        setEditingIndex(index);
        setEditingRole(roles[index].title);
        setIsAdding(false); // Убеждаемся, что режим добавления выключен
    };

    const saveEditedRole = async () => {
        if (editingRole.trim() && editingIndex !== null) {
            const roleToUpdate = roles[editingIndex];
            const newTitle = editingRole.trim();
            
            // Сразу обновляем UI: закрываем инпут и обновляем roleItem
            const updatedRoles = [...roles];
            updatedRoles[editingIndex] = {
                ...updatedRoles[editingIndex],
                title: newTitle
            };
            setRoles(updatedRoles);
            setEditingIndex(null);
            setEditingRole('');
            
            // Отправляем запрос на сервер (результат не влияет на UI)
            const requestData = {
                key: roleToUpdate.key,
                title: newTitle
            };
            
            try {
                const accessToken = getAccessTokenFromCookie();
                
                if (accessToken) {
                    fetch('/api/v1/vacancy/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${accessToken}` 
                        },
                        body: JSON.stringify(requestData)
                    });
                }
            } catch (error) {
                // Игнорируем ошибки - UI уже обновлен
            }
        }
    };

    const cancelEdit = () => {
        setEditingIndex(null);
        setEditingRole('');
    };

    const deleteRole = async (index) => {
        const roleToDelete = roles[index];
        
        console.log('=== НАЧАЛО УДАЛЕНИЯ ВАКАНСИИ ===');
        console.log('Индекс удаляемой роли:', index);
        console.log('Данные роли для удаления:', roleToDelete);
        console.log('Key для удаления:', roleToDelete.key);
        
        // Сразу удаляем из UI
        const updatedRoles = roles.filter((_, i) => i !== index);
        setRoles(updatedRoles);
        
        // Если удаляется редактируемая роль, сбрасываем редактирование
        if (editingIndex === index) {
            setEditingIndex(null);
            setEditingRole('');
            console.log('Сброшено редактирование удаленной роли');
        }
        
        // Отправляем DELETE запрос на сервер
        try {
            const accessToken = getAccessTokenFromCookie();
            console.log('Токен доступа для удаления:', accessToken ? 'найден' : 'НЕ найден');
            
            if (accessToken) {
                const deleteUrl = `/api/v1/vacancy/destroy?key=${roleToDelete.key}`;
                console.log('URL для удаления:', deleteUrl);
                console.log('Метод запроса: DELETE');
                console.log('Заголовки запроса:', {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${accessToken.substring(0, 10)}...`
                });
                
                const response = await fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${accessToken}` 
                    }
                });
                
                console.log('Статус ответа при удалении:', response.status);
                console.log('Статус OK при удалении:', response.ok);
                console.log('Заголовки ответа при удалении:', Object.fromEntries(response.headers.entries()));
                
                const data = await response.json();
                console.log('Данные ответа при удалении:', data);
                
                if (response.ok) {
                    console.log('✅ Удаление выполнено успешно');
                } else {
                    console.error('❌ Ошибка при удалении от сервера');
                    console.error('Response.ok:', response.ok);
                    console.error('Data:', data);
                }
            } else {
                console.error('❌ Нет токена доступа для удаления');
            }
        } catch (error) {
            console.error('❌ ИСКЛЮЧЕНИЕ при удалении:', error);
            console.error('Тип ошибки при удалении:', error.constructor.name);
            console.error('Сообщение ошибки при удалении:', error.message);
            console.error('Stack trace при удалении:', error.stack);
            
            if (error.response) {
                console.error('Ответ сервера при ошибке удаления:', error.response);
                console.error('Статус ошибки удаления:', error.response.status);
                console.error('Данные ошибки удаления:', error.response.data);
            }
        }
        
        console.log('=== УДАЛЕНИЕ ЗАВЕРШЕНО ===');
    };

    const handleKeyPress = (e) => {
        if (e.key === 'Enter') {
            if (editingIndex !== null) {
                saveEditedRole();
            } else {
                saveNewRole();
            }
        } else if (e.key === 'Escape') {
            if (editingIndex !== null) {
                cancelEdit();
            } else {
                cancelAdd();
            }
        }
    };

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
                        <span>Кандидаты</span>
                        <span className="active">Настройки</span>
                    </div>
                    <div style={{display: 'flex', justifyContent: 'space-between', minWidth: '250px'}}>
                        <button id="notifBtn"><img src="img/ring.png" alt="Уведомлений нет" /></button>
                        <button id="exitBtn">Выйти из ЛК <img src="img/arowRight.png" alt="Стрелочка вправо" /></button>
                    </div>
                </div>
            </header>
            <main>
                <section style={{minHeight: '0', flexWrap: 'wrap'}}>
                    <div className="formRow justify-flex-start w-60">
                        <h2>Настройки анкеты</h2>
                    </div>
                    <div className="center-card big w-60">
                        <div className="formRow">
                            <h3 style={{textAlign: 'left'}}>Роли вакансий</h3>
                        </div>
                        <div className="formRow" style={{marginTop: '0'}}>
                            <h4 style={{textAlign: 'left'}}>Роли вакансий, которые отображаются в анкете кандидатов</h4>
                        </div>
                        <div className="formRow justify-flex-start" style={{flexWrap: 'wrap', gap: '1rem'}}>
                            {/* Показываем индикатор загрузки или ошибку */}
                            {isLoadingRoles && (
                                <div style={{padding: '10px', color: '#666', width: '100%'}}>
                                    Загрузка ролей...
                                </div>
                            )}
                            {rolesError && (
                                <div style={{padding: '10px', color: '#e74c3c', width: '100%'}}>
                                    {rolesError}
                                    <button
                                        onClick={loadRoles}
                                        style={{
                                            marginLeft: '10px',
                                            background: 'none',
                                            border: 'none',
                                            color: '#3498db',
                                            cursor: 'pointer',
                                            textDecoration: 'underline'
                                        }}
                                    >
                                        Повторить
                                    </button>
                                </div>
                            )}
                            
                            {/* Отображаем роли, загруженные из API */}
                            {!isLoadingRoles && !rolesError && roles.map((role, index) => (
                                <div key={index} className="roleItem" data-key={role.key} style={{display: 'flex', alignItems: 'center', gap: '8px'}}>
                                    <span>{role.title}</span>
                                    {isEditing && (
                                        <>
                                            <button 
                                                onClick={() => editRole(index)}
                                                style={{
                                                    background: 'none',
                                                    border: 'none',
                                                    cursor: 'pointer',
                                                    padding: '2px',
                                                    display: 'flex',
                                                    alignItems: 'center'
                                                }}
                                            >
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                            </button>
                                            <button 
                                                onClick={() => deleteRole(index)}
                                                style={{
                                                    background: 'none',
                                                    border: 'none',
                                                    cursor: 'pointer',
                                                    padding: '2px',
                                                    display: 'flex',
                                                    alignItems: 'center',
                                                    color: '#dc3545'
                                                }}
                                            >
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                                                    <line x1="18" y1="6" x2="6" y2="18" />
                                                    <line x1="6" y1="6" x2="18" y2="18" />
                                                </svg>
                                            </button>
                                        </>
                                    )}
                                </div>
                            ))}
                            
                            {/* Инпут для добавления новой роли */}
                            {isAdding && (
                                <div className="input-container w-49" style={{minWidth: '200px'}}>
                                    <label htmlFor="newRole" className="formLabel">Новая роль</label>
                                    <input 
                                        style={{width: '100%'}} 
                                        type="text" 
                                        name="newRole" 
                                        id="newRole" 
                                        className="formInput" 
                                        placeholder="Введите название роли"
                                        value={newRole}
                                        onChange={(e) => setNewRole(e.target.value)}
                                        onKeyDown={handleKeyPress}
                                        autoFocus
                                    />
                                </div>
                            )}
                            
                            {/* Инпут для редактирования роли */}
                            {editingIndex !== null && (
                                <div className="input-container w-49" style={{minWidth: '200px'}}>
                                    <label htmlFor="editRole" className="formLabel">Редактирование роли</label>
                                    <input 
                                        style={{width: '100%'}} 
                                        type="text" 
                                        name="editRole" 
                                        id="editRole" 
                                        className="formInput" 
                                        placeholder="Введите название роли"
                                        value={editingRole}
                                        onChange={(e) => setEditingRole(e.target.value)}
                                        onKeyDown={handleKeyPress}
                                        autoFocus
                                    />
                                </div>
                            )}
                        </div>
                        <div className="formRow justify-flex-start" style={{marginTop: '0'}}>
                            <button 
                                className={`formBtn small btn-active`} 
                                onClick={handleAddRole}
                                disabled={isLoadingRoles}
                            >
                                {editingIndex !== null ? 'Подтвердить' : (isAdding ? 'Сохранить роль' : 'Добавить роль')}
                            </button>
                            <button 
                                className={`formBtn small ${isEditing ? 'btn-active' : 'btn-inactive'}`} 
                                disabled={isAdding || editingIndex !== null || isLoadingRoles}
                                onClick={handleEditMode}
                            >
                                {isEditing ? 'Завершить редактирование' : 'Редактировать'}
                            </button>
                        </div>
                    </div>
                </section>
            </main>
        </>
    );
}
        // Main App Component
 function App() {
    const [isCalendarOpen, setIsCalendarOpen] = useState(false);
    const filtersButtonRef = useRef(null);
    
    const handleFiltersClick = () => {
        setIsCalendarOpen(true);
    };
    
    const handleCalendarClose = () => {
        setIsCalendarOpen(false);
    };
    
    return (
        <>
            <Header />
            <main>
                <CandidatesTable 
                    onFiltersClick={handleFiltersClick} 
                    filtersButtonRef={filtersButtonRef}
                />
                <FiltersCalendar
                    isOpen={isCalendarOpen}
                    onClose={handleCalendarClose}
                    filtersButtonRef={filtersButtonRef}
                />
            </main>
        </>
    );
}

        // Render the app
        ReactDOM.render(<App />, document.getElementById('root'));
        <?php echo '@endverbatim'; ?>
    </script>
</body>
</html>