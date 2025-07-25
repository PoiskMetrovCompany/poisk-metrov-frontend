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

    // Функция для расширенного логирования
    const log = (message, data = null, level = 'info') => {
        const timestamp = new Date().toLocaleTimeString();
        const prefix = `[${timestamp}] [${level.toUpperCase()}]`;
        
        if (data) {
            console.log(`${prefix} ${message}`, data);
        } else {
            console.log(`${prefix} ${message}`);
        }
    };

    // Отдельный компонент таймера
    function Timer({ timeLeft, onTimerEnd, isActive }) {
        const [time, setTime] = useState(timeLeft);

        useEffect(() => {
            if (!isActive) return;

            if (time <= 0) {
                log('Таймер завершен, вызываем onTimerEnd');
                onTimerEnd();
                return;
            }

            const timer = setTimeout(() => {
                setTime(time - 1);
            }, 1000);

            return () => clearTimeout(timer);
        }, [time, isActive, onTimerEnd]);

        useEffect(() => {
            log(`Таймер обновлен на ${timeLeft} секунд`);
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
                log('Инициализация маски для телефона');
                const maskOptions = {
                    mask: '+{7}(000) 000-00-00'
                };
                currentMaskRef.current = IMask(phoneInputRef.current, maskOptions);
            }

            return () => {
                if (currentMaskRef.current) {
                    log('Уничтожение маски телефона');
                    currentMaskRef.current.destroy();
                }
            };
        }, [isCodeMode]);

        // Инициализация маски для кода
        useEffect(() => {
            if (phoneInputRef.current && isCodeMode) {
                log('Инициализация маски для кода подтверждения');
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
                log('Фокус установлен на поле ввода кода');
                
                // Дополнительная проверка фокуса через 100мс
                setTimeout(() => {
                    if (phoneInputRef.current === document.activeElement) {
                        log('✅ Фокус подтвержден на поле кода');
                    } else {
                        log('❌ Фокус НЕ на поле кода, устанавливаем повторно');
                        phoneInputRef.current.focus();
                    }
                }, 100);
            }
        }, [isCodeMode]);

        const checkButtonState = () => {
            if (!isCodeMode) {
                const isPhoneValid = phoneValue.length >= 17;
                log(`Проверка состояния кнопки: телефон валиден=${isPhoneValid}, чекбокс=${isCheckboxChecked}, загрузка=${isLoading}`);
                return isPhoneValid && isCheckboxChecked && !isLoading;
            }
            return false;
        };

        // Новая функция для проверки кода и установки таймера
        const checkCodeAndSetTimer = (value) => {
            log('🔢 checkCodeAndSetTimer вызвана', { value });
            
            const enteredCode = value.replace(/\s/g, '').replace(/_/g, '');
            log(`Проверка кода: исходное значение="${value}", очищенный код="${enteredCode}", длина=${enteredCode.length}`);
            
            if (enteredCode.length === 6) {
                log('✓ Код введен полностью (6 символов)!', { code: enteredCode });
                setShowCheckmark(true);
                
                // Если код полный, отправляем сразу без таймера
                log('🚀 Код полный, отправляем немедленно без ожидания!');
                
                // Очищаем таймер если он есть
                if (codeSubmitTimeoutRef.current) {
                    log('⏰ Очищаем таймер - код полный');
                    clearTimeout(codeSubmitTimeoutRef.current);
                    codeSubmitTimeoutRef.current = null;
                }
                
                // Отправляем сразу
                setTimeout(() => {
                    log('✅ Отправляем полный код сразу');
                    sendAuthRequest();
                }, 100); // Небольшая задержка для обновления UI
                
            } else {
                log(`⏳ Код неполный (${enteredCode.length}/6 символов)`, { code: enteredCode });
                setShowCheckmark(false);
                
                // Очищаем предыдущий таймер автоотправки
                if (codeSubmitTimeoutRef.current) {
                    log('⏰ Очищаем предыдущий таймер автоотправки');
                    clearTimeout(codeSubmitTimeoutRef.current);
                    codeSubmitTimeoutRef.current = null;
                }

                // Устанавливаем новый таймер на 2 секунды только для неполного кода
                if (enteredCode.length > 0) {
                    log('⏰ Устанавливаем новый таймер автоотправки на 2 секунды');
                    codeSubmitTimeoutRef.current = setTimeout(() => {
                        log('🚀 ТАЙМЕР СРАБОТАЛ! Начинаем автоотправку неполного кода');
                        const currentCode = value.replace(/\s/g, '').replace(/_/g, '');
                        log('Неполный код для автоотправки', { 
                            originalValue: value, 
                            cleanedCode: currentCode, 
                            codeLength: currentCode.length 
                        });
                        
                        log('✅ Отправляем неполный код после паузы');
                        sendAuthRequest();
                    }, 2000);

                    log('⏰ Таймер установлен, ID:', codeSubmitTimeoutRef.current);
                } else {
                    log('⏰ Код пустой, таймер не устанавливаем');
                }
            }
        };

        const checkCode = (value) => {
            const enteredCode = value.replace(/\s/g, '').replace(/_/g, '');
            log(`Проверка кода: исходное значение="${value}", очищенный код="${enteredCode}", длина=${enteredCode.length}`);
            
            if (enteredCode.length === 6) {
                log('✓ Код введен полностью (6 символов)!', { code: enteredCode });
                setShowCheckmark(true);
                return true;
            } else {
                log(`⏳ Код неполный (${enteredCode.length}/6 символов)`, { code: enteredCode });
                setShowCheckmark(false);
                return false;
            }
        };

        const handleInputChange = (e) => {
            const value = e.target.value;
            log('=== НАЧАЛО handleInputChange ===');
            log('Новое значение в поле ввода', { value, isCodeMode });
            
            // Всегда обновляем состояние
            setPhoneValue(value);

            // Очищаем ошибку при изменении значения
            if (error) {
                log('Очищаем предыдущую ошибку');
                setError('');
            }

            // В режиме кода обрабатываем автоотправку
            if (isCodeMode) {
                log('🔢 Режим ввода кода активен, запускаем checkCodeAndSetTimer');
                checkCodeAndSetTimer(value);
            }
            
            log('=== КОНЕЦ handleInputChange ===');
        };

        const handleCheckboxChange = (e) => {
            log('Изменение состояния чекбокса', { checked: e.target.checked });
            setIsCheckboxChecked(e.target.checked);
            if (error) {
                setError('');
            }
        };

        // Функция для отправки запроса на получение кода
        const sendCodeRequest = async (phone) => {
            try {
                log('📤 Начинаем отправку запроса на получение кода', { phone });
                setIsLoading(true);
                setError('');

                const response = await axios.post('/api/v1/account/set-code', {
                    phone: phone
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                log('📥 Получен ответ от сервера', response.data);

                if (response.data.request) {
                    setUserAttributes(response.data.attributes);
                    log('✅ Код отправлен успешно', response.data);
                    return true;
                } else {
                    log('❌ Ошибка: request=false в ответе сервера');
                    setError('Ошибка при отправке кода');
                    return false;
                }
            } catch (error) {
                log('❌ Ошибка при отправке запроса на код', error, 'error');

                if (error.response) {
                    log('Детали ошибки ответа сервера', {
                        status: error.response.status,
                        data: error.response.data
                    }, 'error');
                    
                    if (error.response.status === 404) {
                        setError('Пользователь не найден');
                    } else {
                        setError(error.response.data?.error || 'Ошибка сервера');
                    }
                } else if (error.request) {
                    log('Ошибка запроса (нет ответа от сервера)', error.request, 'error');
                    setError('Ошибка соединения с сервером');
                } else {
                    log('Общая ошибка запроса', error.message, 'error');
                    setError('Ошибка при отправке запроса');
                }
                return false;
            } finally {
                setIsLoading(false);
                log('Завершена отправка запроса на код');
            }
        };

        // Функция для отправки запроса на аутентификацию
        const sendAuthRequest = async () => {
            log('🔐 === НАЧАЛО АУТЕНТИФИКАЦИИ ===');
            
            // Очищаем таймер автоотправки если он есть
            if (codeSubmitTimeoutRef.current) {
                log('⏰ Очищаем активный таймер автоотправки');
                clearTimeout(codeSubmitTimeoutRef.current);
                codeSubmitTimeoutRef.current = null;
            }

            try {
                setIsAuthLoading(true);
                setError('');

                let enteredCode = phoneValue;
                log('Исходное значение кода из поля', { phoneValue });

                if (currentMaskRef.current && currentMaskRef.current.unmaskedValue) {
                    enteredCode = currentMaskRef.current.unmaskedValue;
                    log('Получен код из маски', { 
                        maskedValue: phoneValue,
                        unmaskedValue: enteredCode 
                    });
                } else {
                    enteredCode = phoneValue.replace(/\s/g, '').replace(/_/g, '');
                    log('Код очищен вручную', { 
                        original: phoneValue,
                        cleaned: enteredCode 
                    });
                }

                log('🔑 Финальный код для отправки', { 
                    code: enteredCode, 
                    length: enteredCode.length 
                });

                if (enteredCode.length === 0) {
                    log('❌ Код пустой, прерываем аутентификацию');
                    setError('Введите код из СМС');
                    return false;
                }

                const phoneToAuth = userAttributes?.phone;
                if (!phoneToAuth) {
                    log('❌ Номер телефона не найден в userAttributes', userAttributes);
                    setError('Ошибка: номер телефона не найден');
                    return false;
                }

                log('📤 Отправляем запрос на аутентификацию', {
                    phone: phoneToAuth,
                    code: enteredCode,
                    codeLength: enteredCode.length
                });

                const response = await axios.post('/api/v1/account/auth', {
                    phone: phoneToAuth,
                    code: enteredCode
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                log('📥 Ответ сервера на аутентификацию', response.data);
                
                if (response.data.request && response.data.attributes) {
                    log('✅ Аутентификация успешна - выполняем немедленный редирект!', response.data);

                    if (response.data.attributes.access_token) {
                        const expirationDate = new Date();
                        expirationDate.setTime(expirationDate.getTime() + (30 * 24 * 60 * 60 * 1000));
                        document.cookie = `access_token=${response.data.attributes.access_token}; expires=${expirationDate.toUTCString()}; path=/; SameSite=Strict`;
                        
                        log('🍪 Токен сохранен в cookie', { 
                            token: response.data.attributes.access_token.substring(0, 20) + '...',
                            expires: expirationDate.toUTCString()
                        });
                        
                        const redirectUrl = response.data.attributes.user.role === "candidate" ? '/profile-candidates/' : '/profile-candidates/security/';
                        log('🔀 Немедленное перенаправление на', { 
                            role: response.data.attributes.user.role,
                            url: redirectUrl 
                        });
                        
                        // Немедленное перенаправление без показа экрана успеха
                        window.location.href = redirectUrl;
                        return true;
                    }

                    return true;
                } else {
                    log('❌ Неуспешная аутентификация', {
                        request: response.data.request,
                        hasAttributes: !!response.data.attributes
                    });
                    setError('Ошибка при аутентификации');
                    return false;
                }
            } catch (error) {
                log('❌ Ошибка при аутентификации', error, 'error');

                if (error.response) {
                    log('Детали ошибки аутентификации', {
                        status: error.response.status,
                        data: error.response.data
                    }, 'error');
                    
                    if (error.response.status === 401) {
                        setError('Неверный код');
                        log('🚫 Неверный код подтверждения');
                    } else if (error.response.status === 404) {
                        setError('Пользователь не найден');
                        log('🚫 Пользователь не найден');
                    } else {
                        setError(error.response.data?.error || 'Ошибка сервера');
                    }
                } else if (error.request) {
                    log('Ошибка соединения при аутентификации', error.request, 'error');
                    setError('Ошибка соединения с сервером');
                } else {
                    log('Общая ошибка аутентификации', error.message, 'error');
                    setError('Ошибка при отправке запроса');
                }
                return false;
            } finally {
                setIsAuthLoading(false);
                log('🔐 === КОНЕЦ АУТЕНТИФИКАЦИИ ===');
            }
        };

        const startTimer = () => {
            log('⏰ Запуск таймера на 60 секунд');
            setTimeLeft(60);
            setTimerActive(true);
        };

        const handleTimerEnd = () => {
            log('⏰ Таймер завершен');
            setTimerActive(false);
        };

        const handleGetCodeClick = async (e) => {
            e.preventDefault();
            log('🖱️ Клик по кнопке получения кода', { isCodeMode });

            if (!isCodeMode) {
                log('📱 Первичная отправка кода на номер', { phone: phoneValue });
                const success = await sendCodeRequest(phoneValue);

                if (success) {
                    log('✅ Переход в режим ввода кода');
                    setIsCodeMode(true);
                    setPhoneValue('');
                    setShowCheckmark(false);
                    startTimer();
                }
            } else {
                log('🔄 Повторная отправка кода');
                const phoneToResend = userAttributes?.phone || phoneValue;
                log('Номер для повторной отправки', { phoneToResend });
                
                const success = await sendCodeRequest(phoneToResend);

                if (success) {
                    log('✅ Код повторно отправлен');
                    setPhoneValue('');
                    setShowCheckmark(false);
                    startTimer();
                }
            }
        };

        const handleChangeNumber = (e) => {
            e.preventDefault();
            log('🔄 Смена номера телефона');

            // Очищаем таймер автоотправки
            if (codeSubmitTimeoutRef.current) {
                log('⏰ Очищаем таймер автоотправки при смене номера');
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
                    log('Фокус установлен на поле телефона');
                }
            }, 0);
        };

        // Очистка таймеров при размонтировании компонента
        useEffect(() => {
            return () => {
                if (codeSubmitTimeoutRef.current) {
                    log('🧹 Очистка таймера при размонтировании компонента');
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

        // Убираем проверку на аутентификацию - сразу редиректим
        // if (isAuthenticated && authResult) { ... } - блок удален

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
                                        onInput={(e) => {
                                            // Дополнительный обработчик для режима кода
                                            log('📝 onInput событие', { 
                                                value: e.target.value, 
                                                isCodeMode 
                                            });
                                            
                                            if (isCodeMode) {
                                                log('🔢 onInput в режиме кода - дублируем обработку');
                                                // Дублируем обработку для режима кода
                                                const value = e.target.value;
                                                setPhoneValue(value);
                                                checkCodeAndSetTimer(value);
                                            }
                                        }}
                                        onKeyUp={(e) => {
                                            // Отслеживаем отпускание клавиш
                                            log('⌨️ onKeyUp событие', { 
                                                key: e.key, 
                                                value: e.target.value,
                                                isCodeMode 
                                            });
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