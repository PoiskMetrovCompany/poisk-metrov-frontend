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
                        window.location.href = response.data.attributes.user.role === "candidate" ? '/profile-candidates/' : '/profile-candidates/security/'
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
                        <img src="/img/Logo с текстом.png" alt="Картинка с логотипом агенства и подписью Поиск метров" />
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
                    <img src="/img/Logo с текстом.png" alt="Картинка с логотипом агенства и подписью Поиск метров" />
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


    function App() {
        const [isCalendarOpen, setIsCalendarOpen] = useState(false);
        const [currentView, setCurrentView] = useState('table'); // 'table' или 'form'
        const [selectedCandidate, setSelectedCandidate] = useState(null);
        const filtersButtonRef = useRef(null);

        const handleFiltersClick = () => {
            console.log('Кнопка фильтры нажата'); // Для отладки
            setIsCalendarOpen(true);
        };

        const handleCalendarClose = () => {
            console.log('Закрытие календаря'); // Для отладки
            setIsCalendarOpen(false);
        };

        // Функция для обработки клика по строке таблицы
        const handleRowClick = (vacancyKey) => {
            setSelectedCandidate(vacancyKey);
            setCurrentView('form');
        };

        // Функция для возврата к таблице
        const handleBackToTable = () => {
            setCurrentView('table');
            setSelectedCandidate(null);
        };

        return (
            <>
                <CandidateRegForm />
            </>
        );
    }

    // Монтируем главное приложение
    ReactDOM.render(React.createElement(App), document.getElementById('root'));
    <?php echo '@endverbatim'; ?>
</script>
</body>
</html>
