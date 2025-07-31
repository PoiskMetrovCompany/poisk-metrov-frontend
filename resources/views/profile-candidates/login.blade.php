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

    // Отдельный компонент таймера
    function Timer({ timeLeft, onTimerEnd, isActive }) {
        const [time, setTime] = useState(timeLeft);

        useEffect(() => {
            if (!isActive) return;

            if (time <= 0) {
                onTimerEnd();
                return;
            }

            const timer = setTimeout(() => {
                setTime(time - 1);
            }, 1000);

            return () => clearTimeout(timer);
        }, [time, isActive, onTimerEnd]);

        useEffect(() => {
            setTime(timeLeft);
        }, [timeLeft]);

        if (!isActive || time <= 0) return null;

        const minutes = Math.floor(time / 60);
        const seconds = time % 60;
        const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        return <span>{timeString}</span>;
    }

    // Главный компонент регистрации кандидата
    function CandidateRegForm() {
        const [isCodeMode, setIsCodeMode] = useState(false);
        const [phoneValue, setPhoneValue] = useState('');
        const [isCheckboxChecked, setIsCheckboxChecked] = useState(false);
        const [showCheckmark, setShowCheckmark] = useState(false);
        const [isLoading, setIsLoading] = useState(false);
        const [isAuthLoading, setIsAuthLoading] = useState(false);
        const [error, setError] = useState('');
        const [userAttributes, setUserAttributes] = useState(null);
        const [isAuthenticated, setIsAuthenticated] = useState(false);
        const [authResult, setAuthResult] = useState(null);
        const [timerActive, setTimerActive] = useState(false);
        const [timeLeft, setTimeLeft] = useState(60);
        
        const phoneInputRef = useRef(null);
        const currentMaskRef = useRef(null);
        const codeSubmitTimeoutRef = useRef(null);

        // Инициализация маски для телефона
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

        // Инициализация маски для кода
        useEffect(() => {
            if (phoneInputRef.current && isCodeMode) {
                if (currentMaskRef.current) {
                    currentMaskRef.current.destroy();
                }
                const maskOptions = {
                    mask: ' 0 0 0 0 ',
                    lazy: false,
                    placeholderChar: " _ "
                };
                currentMaskRef.current = IMask(phoneInputRef.current, maskOptions);
                
                phoneInputRef.current.focus();
                
                // Дополнительная проверка фокуса через 100мс
                setTimeout(() => {
                    if (phoneInputRef.current !== document.activeElement) {
                        phoneInputRef.current.focus();
                    }
                }, 100);
            }
        }, [isCodeMode]);

        const checkButtonState = () => {
            if (!isCodeMode) {
                const isPhoneValid = phoneValue.length >= 17;
                return isPhoneValid && isCheckboxChecked && !isLoading;
            }
            return false;
        };

        // Функция для проверки кода и установки таймера
        const checkCodeAndSetTimer = (value) => {
            const enteredCode = value.replace(/\s/g, '').replace(/_/g, '');
            
            if (enteredCode.length === 6) {
                setShowCheckmark(true);
                
                // Очищаем таймер если он есть
                if (codeSubmitTimeoutRef.current) {
                    clearTimeout(codeSubmitTimeoutRef.current);
                    codeSubmitTimeoutRef.current = null;
                }
                
                // Отправляем сразу
                setTimeout(() => {
                    sendAuthRequest();
                }, 100);
                
            } else {
                setShowCheckmark(false);
                
                // Очищаем предыдущий таймер автоотправки
                if (codeSubmitTimeoutRef.current) {
                    clearTimeout(codeSubmitTimeoutRef.current);
                    codeSubmitTimeoutRef.current = null;
                }

                // Устанавливаем новый таймер на 2 секунды только для неполного кода
                if (enteredCode.length > 0) {
                    codeSubmitTimeoutRef.current = setTimeout(() => {
                        sendAuthRequest();
                    }, 2000);
                }
            }
        };

        const handleInputChange = (e) => {
            const value = e.target.value;
            
            // Всегда обновляем состояние
            setPhoneValue(value);

            // Очищаем ошибку при изменении значения
            if (error) {
                setError('');
            }

            // В режиме кода обрабатываем автоотправку
            if (isCodeMode) {
                checkCodeAndSetTimer(value);
            }
        };

        const handleCheckboxChange = (e) => {
            setIsCheckboxChecked(e.target.checked);
            if (error) {
                setError('');
            }
        };

        // Функция для отправки запроса на получение кода
        const sendCodeRequest = async (phone) => {
            try {
                setIsLoading(true);
                setError('');

                const response = await axios.post('/api/v1/account/set-code', {
                    phone: phone
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (response.data.request) {
                    setUserAttributes(response.data.attributes);
                    return true;
                } else {
                    setError('Ошибка при отправке кода');
                    return false;
                }
            } catch (error) {
                if (error.response) {
                    if (error.response.status === 404) {
                        setError('Пользователь не найден');
                    } else {
                        setError(error.response.data?.error || 'Ошибка сервера');
                    }
                } else if (error.request) {
                    setError('Ошибка соединения с сервером');
                } else {
                    setError('Ошибка при отправке запроса');
                }
                return false;
            } finally {
                setIsLoading(false);
            }
        };

        // Функция для отправки запроса на аутентификацию
        const sendAuthRequest = async () => {
            // Очищаем таймер автоотправки если он есть
            if (codeSubmitTimeoutRef.current) {
                clearTimeout(codeSubmitTimeoutRef.current);
                codeSubmitTimeoutRef.current = null;
            }

            try {
                setIsAuthLoading(true);
                setError('');

                let enteredCode = phoneValue;

                if (currentMaskRef.current && currentMaskRef.current.unmaskedValue) {
                    enteredCode = currentMaskRef.current.unmaskedValue;
                } else {
                    enteredCode = phoneValue.replace(/\s/g, '').replace(/_/g, '');
                }

                if (enteredCode.length === 0) {
                    setError('Введите код из СМС');
                    return false;
                }

                const phoneToAuth = userAttributes?.phone;
                if (!phoneToAuth) {
                    setError('Ошибка: номер телефона не найден');
                    return false;
                }

                const response = await axios.post('/api/v1/account/auth', {
                    phone: phoneToAuth,
                    code: enteredCode
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.data.request && response.data.attributes) {
                    if (response.data.attributes.access_token) {
                        const expirationDate = new Date();
                        expirationDate.setTime(expirationDate.getTime() + (30 * 24 * 60 * 60 * 1000));
                        document.cookie = `access_token=${response.data.attributes.access_token}; expires=${expirationDate.toUTCString()}; path=/; SameSite=Strict`;
                        
                        const redirectUrl = response.data.attributes.user.role === "candidate" ? '/profile-candidates/' : '/profile-candidates/security/';
                        
                        // Немедленное перенаправление без показа экрана успеха
                        window.location.href = redirectUrl;
                        return true;
                    }

                    return true;
                } else {
                    setError('Ошибка при аутентификации');
                    return false;
                }
            } catch (error) {
                if (error.response) {
                    if (error.response.status === 401) {
                        setError('Неверный код');
                    } else if (error.response.status === 404) {
                        setError('Пользователь не найден');
                    } else {
                        setError(error.response.data?.error || 'Ошибка сервера');
                    }
                } else if (error.request) {
                    setError('Ошибка соединения с сервером');
                } else {
                    setError('Ошибка при отправке запроса');
                }
                return false;
            } finally {
                setIsAuthLoading(false);
            }
        };

        const startTimer = () => {
            setTimeLeft(60);
            setTimerActive(true);
        };

        const handleTimerEnd = () => {
            setTimerActive(false);
        };

        const handleGetCodeClick = async (e) => {
            e.preventDefault();

            if (!isCodeMode) {
                const success = await sendCodeRequest(phoneValue);

                if (success) {
                    setIsCodeMode(true);
                    setPhoneValue('');
                    setShowCheckmark(false);
                    startTimer();
                }
            } else {
                const phoneToResend = userAttributes?.phone || phoneValue;
                
                const success = await sendCodeRequest(phoneToResend);

                if (success) {
                    setPhoneValue('');
                    setShowCheckmark(false);
                    startTimer();
                }
            }
        };

        const handleChangeNumber = (e) => {
            e.preventDefault();

            // Очищаем таймер автоотправки
            if (codeSubmitTimeoutRef.current) {
                clearTimeout(codeSubmitTimeoutRef.current);
                codeSubmitTimeoutRef.current = null;
            }

            setIsCodeMode(false);
            setPhoneValue('');
            setShowCheckmark(false);
            setError('');
            setUserAttributes(null);
            setIsAuthenticated(false);
            setAuthResult(null);
            setTimerActive(false);

            setTimeout(() => {
                if (phoneInputRef.current) {
                    phoneInputRef.current.focus();
                }
            }, 0);
        };

        // Очистка таймеров при размонтировании компонента
        useEffect(() => {
            return () => {
                if (codeSubmitTimeoutRef.current) {
                    clearTimeout(codeSubmitTimeoutRef.current);
                }
            };
        }, []);

        const getButtonText = () => {
            if (isLoading) {
                return "Отправка...";
            }
            if (!isCodeMode) {
                return "Получить код";
            }
            
            return timerActive ? (
                <>
                    Получить код повторно <Timer 
                        timeLeft={timeLeft} 
                        onTimerEnd={handleTimerEnd} 
                        isActive={timerActive} 
                    />
                </>
            ) : "Получить код повторно";
        };

        const getButtonClass = () => {
            if (isLoading) {
                return "formBtn btn-inactive";
            }
            if (!isCodeMode) {
                return checkButtonState() ? "formBtn btn-active" : "formBtn btn-inactive";
            }
            // В режиме кода кнопка активна только если таймер не идет
            return !timerActive ? "formBtn btn-active" : "formBtn btn-inactive";
        };

        const isButtonDisabled = () => {
            if (isLoading) return true;
            if (!isCodeMode) return !checkButtonState();
            // В режиме кода кнопка заблокирована пока идет таймер
            return timerActive;
        };

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
                                        {isCodeMode ? "Последние 4 цифры номера" : "Телефон"}
                                    </label>
                                    <input
                                        type="tel"
                                        name="phoneNumber"
                                        id="phoneNumber"
                                        className="formInput"
                                        placeholder={isCodeMode ? "Введите последние 4 цифры номера" : "Введите номер"}
                                        value={phoneValue}
                                        onChange={handleInputChange}
                                        onInput={(e) => {
                                            if (isCodeMode) {
                                                const value = e.target.value;
                                                setPhoneValue(value);
                                                checkCodeAndSetTimer(value);
                                            }
                                        }}
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

                                <div
                                    className="checkboxRow"
                                    id="checkboxRow"
                                    style={{display: isCodeMode ? 'none' : 'flex'}}
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
                                <>
                                    <a
                                        href="#"
                                        id="changeNumber"
                                        onClick={handleChangeNumber}
                                    >
                                        Изменить номер
                                    </a>
                                    <p style={{fontSize: '14px', color: '#666', marginTop: '10px'}}>
                                    </p>
                                </>
                            )}
                        </div>
                    </section>
                </main>
            </>
        );
    }

    function App() {
        const [isCalendarOpen, setIsCalendarOpen] = useState(false);
        const [currentView, setCurrentView] = useState('table');
        const [selectedCandidate, setSelectedCandidate] = useState(null);
        const filtersButtonRef = useRef(null);

        const handleFiltersClick = () => {
            setIsCalendarOpen(true);
        };

        const handleCalendarClose = () => {
            setIsCalendarOpen(false);
        };

        const handleRowClick = (vacancyKey) => {
            setSelectedCandidate(vacancyKey);
            setCurrentView('form');
        };

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