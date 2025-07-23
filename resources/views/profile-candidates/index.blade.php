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
        // function CandidateRegForm() {
        //     const [isCodeMode, setIsCodeMode] = useState(false);
        //     const [isPhoneValidated, setIsPhoneValidated] = useState(false);
        //     const [phoneValue, setPhoneValue] = useState('');
        //     const [isCheckboxChecked, setIsCheckboxChecked] = useState(false);
        //     const [showCheckmark, setShowCheckmark] = useState(false);
        //     const [isLoading, setIsLoading] = useState(false);
        //     const [isAuthLoading, setIsAuthLoading] = useState(false);
        //     const [error, setError] = useState('');
        //     const [userAttributes, setUserAttributes] = useState(null);
        //     const [isAuthenticated, setIsAuthenticated] = useState(false);
        //     const [authResult, setAuthResult] = useState(null);
            
        //     const phoneInputRef = useRef(null);
        //     const currentMaskRef = useRef(null);

        //     useEffect(() => {
        //         if (phoneInputRef.current && !isCodeMode) {
        //             const maskOptions = {
        //                 mask: '+{7}(000) 000-00-00'
        //             };
        //             currentMaskRef.current = IMask(phoneInputRef.current, maskOptions);
        //         }

        //         return () => {
        //             if (currentMaskRef.current) {
        //                 currentMaskRef.current.destroy();
        //             }
        //         };
        //     }, [isCodeMode]);

        //     useEffect(() => {
        //         if (phoneInputRef.current && isCodeMode) {
        //             if (currentMaskRef.current) {
        //                 currentMaskRef.current.destroy();
        //             }
        //             const maskOptions = {
        //                 mask: ' 0 0 0 0 0 0 ',
        //                 lazy: false,
        //                 placeholderChar: " _ "
        //             };
        //             currentMaskRef.current = IMask(phoneInputRef.current, maskOptions);
        //             phoneInputRef.current.focus();
        //         }
        //     }, [isCodeMode]);

        //     const checkButtonState = () => {
        //         if (!isCodeMode) {
        //             const isPhoneValid = phoneValue.length >= 17;
        //             return isPhoneValid && isCheckboxChecked && !isLoading;
        //         }
        //         return false;
        //     };

        //     const checkCode = (value) => {
        //         const enteredCode = value.replace(/\s/g, '').replace(/_/g, '');
        //         if (enteredCode.length === 6) {
        //             setShowCheckmark(true);
        //             console.log('Код введен полностью!');
        //         } else {
        //             setShowCheckmark(false);
        //         }
        //     };

        //     const handleInputChange = (e) => {
        //         const value = e.target.value;
        //         console.log('handleInputChange вызван с значением:', value);
        //         console.log('isCodeMode:', isCodeMode);
        //         setPhoneValue(value);
                
        //         // Очищаем ошибку при изменении значения
        //         if (error) {
        //             setError('');
        //         }
                
        //         if (isCodeMode) {
        //             checkCode(value);
        //         }
        //     };

        //     const handleCheckboxChange = (e) => {
        //         setIsCheckboxChecked(e.target.checked);
        //         // Очищаем ошибку при изменении чекбокса
        //         if (error) {
        //             setError('');
        //         }
        //     };

        //     // Функция для отправки запроса на получение кода
        //     const sendCodeRequest = async (phone) => {
        //         try {
        //             setIsLoading(true);
        //             setError('');
                    
        //             // Отправляем запрос на сервер
        //             const response = await axios.post('/api/v1/account/set-code', {
        //                 phone: phone
        //             }, {
        //                 headers: {
        //                     'Content-Type': 'application/json'
        //                 }
        //             });

        //             // Проверяем успешность запроса
        //             if (response.data.request) {
        //                 setUserAttributes(response.data.attributes);
        //                 console.log('Код отправлен успешно:', response.data);
        //                 return true;
        //             } else {
        //                 setError('Ошибка при отправке кода');
        //                 return false;
        //             }
        //         } catch (error) {
        //             console.error('Ошибка при отправке запроса:', error);
                    
        //             if (error.response) {
        //                 // Сервер ответил с кодом ошибки
        //                 if (error.response.status === 404) {
        //                     setError('Пользователь не найден');
        //                 } else {
        //                     setError(error.response.data?.error || 'Ошибка сервера');
        //                 }
        //             } else if (error.request) {
        //                 // Запрос был отправлен, но ответа не получено
        //                 setError('Ошибка соединения с сервером');
        //             } else {
        //                 // Ошибка при настройке запроса
        //                 setError('Ошибка при отправке запроса');
        //             }
        //             return false;
        //         } finally {
        //             setIsLoading(false);
        //         }
        //     };

        //     // Функция для отправки запроса на аутентификацию
        //     const sendAuthRequest = async () => {
        //         console.log('sendAuthRequest вызвана');
        //         try {
        //             setIsAuthLoading(true);
        //             setError('');
                    
        //             // Получаем введенный код из значения инпута или маски
        //             let enteredCode = phoneValue;
                    
        //             // Если используется маска, попробуем получить значение из нее
        //             if (currentMaskRef.current && currentMaskRef.current.unmaskedValue) {
        //                 enteredCode = currentMaskRef.current.unmaskedValue;
        //                 console.log('Код из маски:', enteredCode);
        //             } else {
        //                 // Убираем пробелы и подчеркивания из обычного значения
        //                 enteredCode = phoneValue.replace(/\s/g, '').replace(/_/g, '');
        //                 console.log('Код из phoneValue:', enteredCode);
        //             }
                    
        //             console.log('Финальный введенный код:', enteredCode);
                    
        //             // Если код не введен, показываем ошибку
        //             if (enteredCode.length === 0) {
        //                 console.log('Код не введен');
        //                 setError('Введите код из СМС');
        //                 return false;
        //             }

        //             // Получаем номер телефона из userAttributes
        //             const phoneToAuth = userAttributes?.phone;
        //             console.log('Номер для аутентификации:', phoneToAuth);
        //             if (!phoneToAuth) {
        //                 console.log('Номер телефона не найден');
        //                 setError('Ошибка: номер телефона не найден');
        //                 return false;
        //             }

        //             console.log('Отправляем запрос на аутентификацию...');
        //             // Отправляем запрос на аутентификацию
        //             const response = await axios.post('/api/v1/account/auth', {
        //                 phone: phoneToAuth,
        //                 code: enteredCode
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
        //                 console.log('Аутентификация успешна:', response.data);
                        
        //                 // Сохраняем токен в cookie
        //                 if (response.data.attributes.access_token) {
        //                     // Устанавливаем cookie с токеном на 30 дней
        //                     const expirationDate = new Date();
        //                     expirationDate.setTime(expirationDate.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30 дней
        //                     document.cookie = `access_token=${response.data.attributes.access_token}; expires=${expirationDate.toUTCString()}; path=/; SameSite=Strict`;
        //                     console.log('Токен сохранен в cookie:', response.data.attributes.access_token);
        //                 }
                        
        //                 return true;
        //             } else {
        //                 console.log('Ошибка в ответе сервера');
        //                 setError('Ошибка при аутентификации');
        //                 return false;
        //             }
        //         } catch (error) {
        //             console.error('Ошибка при аутентификации:', error);
                    
        //             if (error.response) {
        //                 // Сервер ответил с кодом ошибки
        //                 console.log('Ошибка ответа сервера:', error.response.status, error.response.data);
        //                 if (error.response.status === 401) {
        //                     setError('Неверный код');
        //                 } else if (error.response.status === 404) {
        //                     setError('Пользователь не найден');
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
        //             console.log('Завершение sendAuthRequest');
        //             setIsAuthLoading(false);
        //         }
        //     };

        //     const handleGetCodeClick = async (e) => {
        //         e.preventDefault();
                
        //         if (!isCodeMode) {
        //             // Первичная отправка кода
        //             const success = await sendCodeRequest(phoneValue);
                    
        //             if (success) {
        //                 setIsCodeMode(true);
        //                 setPhoneValue('');
        //                 setShowCheckmark(false);
        //             }
        //         } else {
        //             // Повторная отправка кода
        //             // Получаем сохраненный номер телефона из userAttributes
        //             const phoneToResend = userAttributes?.phone || phoneValue;
        //             const success = await sendCodeRequest(phoneToResend);
                    
        //             if (success) {
        //                 setPhoneValue('');
        //                 setShowCheckmark(false);
        //             }
        //         }
        //     };

        //     const handleSendCodeClick = async (e) => {
        //         e.preventDefault();
        //         console.log('Кнопка "Отправить код" нажата');
        //         console.log('Текущее значение phoneValue:', phoneValue);
        //         console.log('userAttributes:', userAttributes);
        //         await sendAuthRequest();
        //     };

        //     const handleChangeNumber = (e) => {
        //         e.preventDefault();
                
        //         setIsCodeMode(false);
        //         setPhoneValue('');
        //         setShowCheckmark(false);
        //         setError('');
        //         setUserAttributes(null);
        //         setIsAuthenticated(false);
        //         setAuthResult(null);
                
        //         setTimeout(() => {
        //             if (phoneInputRef.current) {
        //                 phoneInputRef.current.focus();
        //             }
        //         }, 0);
        //     };

        //     const getButtonText = () => {
        //         if (isLoading) {
        //             return "Отправка...";
        //         }
        //         return isCodeMode ? "Получить код повторно" : "Получить код";
        //     };

        //     const getButtonClass = () => {
        //         if (isLoading) {
        //             return "formBtn btn-inactive";
        //         }
        //         if (!isCodeMode) {
        //             return checkButtonState() ? "formBtn btn-active" : "formBtn btn-inactive";
        //         }
        //         return "formBtn btn-active";
        //     };

        //     const isButtonDisabled = () => {
        //         if (isLoading) return true;
        //         if (!isCodeMode) return !checkButtonState();
        //         return false;
        //     };

        //     const getSendCodeButtonClass = () => {
        //         if (isAuthLoading) {
        //             return "formBtn btn-inactive";
        //         }
        //         return "formBtn btn-active";
        //     };

        //     const isSendCodeButtonDisabled = () => {
        //         return isAuthLoading;
        //     };

        //     // Если пользователь аутентифицирован, показываем результат
        //     if (isAuthenticated && authResult) {
        //         return (
        //             <>
        //                 <header>
        //                     <img src="img/Logo с текстом.png" alt="Картинка с логотипом агенства и подписью Поиск метров" />
        //                 </header>

        //                 <main>
        //                     <section>
        //                         <div className="center-card">
        //                             <h1>Аутентификация успешна!</h1>
        //                             <p>Добро пожаловать в систему</p>
                                    
        //                             <div>
        //                                 <strong>Информация о пользователе:</strong><br />
        //                                 ID: {authResult.user.id}<br />
        //                                 Роль: {authResult.user.role}<br />
        //                                 Телефон: {authResult.user.phone}<br />
        //                                 Токен: {authResult.access_token.substring(0, 20)}...
        //                             </div>
                                    
        //                             <button 
        //                                 className="formBtn btn-active"
        //                                 onClick={handleChangeNumber}
        //                                 style={ {marginTop: '20px'} }
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
        //                         <h1>Регистрация кандидата</h1>
        //                         <p>Введите номер телефона, чтобы авторизоваться в системе и получить доступ к анкете кандидата</p>

        //                         <form action="#">
        //                             <div className="input-container">
        //                                 <label htmlFor="phoneNumber" id="formLabel" className="formLabel">
        //                                     {isCodeMode ? "Код из СМС" : "Телефон"}
        //                                 </label>
        //                                 <input 
        //                                     type="tel" 
        //                                     name="phoneNumber" 
        //                                     id="phoneNumber" 
        //                                     className="formInput" 
        //                                     placeholder={isCodeMode ? "Введите код из СМС" : "Введите номер"}
        //                                     value={phoneValue}
        //                                     onChange={handleInputChange}
        //                                     ref={phoneInputRef}
        //                                     disabled={isLoading || isAuthLoading}
        //                                 />
        //                                 {showCheckmark && (
        //                                     <div className="checkmark-icon" id="checkmarkIcon">
        //                                         <svg viewBox="0 0 24 24">
        //                                             <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
        //                                         </svg>
        //                                     </div>
        //                                 )}
        //                             </div>

        //                             {/* Отображение ошибки */}
        //                             {error && (
        //                                 <div className="error-message">
        //                                     {error}
        //                                 </div>
        //                             )}

        //                             <button 
        //                                 id="getCodeBtn" 
        //                                 className={getButtonClass()}
        //                                 disabled={isButtonDisabled()}
        //                                 onClick={handleGetCodeClick}
        //                             >
        //                                 {getButtonText()}
        //                             </button><br />
                                    
        //                             {/* Кнопка отправки кода - показывается только в режиме ввода кода */}
        //                             {isCodeMode && (
        //                                 <button 
        //                                     id="sendCodeBtn" 
        //                                     className={getSendCodeButtonClass()}
        //                                     disabled={isSendCodeButtonDisabled()}
        //                                     onClick={handleSendCodeClick}
        //                                     style={ {marginTop: '10px'} }
        //                                 >
        //                                     {isAuthLoading ? "Отправка..." : "Отправить код"}
        //                                 </button>
        //                             )}
                                    
        //                             <div 
        //                                 className="checkboxRow" 
        //                                 id="checkboxRow"
        //                                 style={ {display: isCodeMode ? 'none' : 'flex'} }
        //                             >
        //                                 <label className="custom-checkbox" htmlFor="personalData">
        //                                     <input 
        //                                         type="checkbox" 
        //                                         name="personalData" 
        //                                         id="personalData"
        //                                         checked={isCheckboxChecked}
        //                                         onChange={handleCheckboxChange}
        //                                         disabled={isLoading || isAuthLoading}
        //                                     />
        //                                     <span className="checkmark"></span>
        //                                 </label>
        //                                 <label htmlFor="personalData">
        //                                     Я даю согласие на обработку <span>своих персональных данных</span>
        //                                 </label>
        //                             </div>
        //                         </form>
                                
        //                         {isCodeMode && (
        //                             <a 
        //                                 href="#" 
        //                                 id="changeNumber"
        //                                 onClick={handleChangeNumber}
        //                             >
        //                                 Изменить номер
        //                             </a>
        //                         )}

        //                         {/* Отладочная информация (можно убрать в продакшене) */}
        //                         {userAttributes && !isAuthenticated && (
        //                             <div style={ {marginTop: '20px', fontSize: '12px', color: '#666'} }>
        //                                 <strong>Отладка (получение кода):</strong><br />
        //                                 ID: {userAttributes.id}<br />
        //                                 Роль: {userAttributes.role}<br />
        //                                 Телефон: {userAttributes.phone}
        //                             </div>
        //                         )}
        //                     </div>
        //                 </section>
        //             </main>
        //         </>
        //     );
        // }

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
            const loadVacancies = async () => {
                try {
                    setIsLoadingVacancies(true);
                    setVacancyError('');

                    const accessToken = getAccessTokenFromCookie();
                    
                    if (!accessToken) {
                        setVacancyError('Токен доступа не найден');
                        return;
                    }

                    const response = await axios.get('/api/v1/vacancy/', {
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${accessToken}` 
                        }
                    });

                    if (response.data.response && response.data.attributes) {
                        // Преобразуем данные API в массив строк для select
                        const vacancies = response.data.attributes.map(vacancy => vacancy.title);
                        setVacancyOptions(vacancies);
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
                    } else if (error.request) {
                        setVacancyError('Ошибка соединения с сервером');
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

                    const response = await axios.get('/api/v1/marital-statuses/', {
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${accessToken}` 
                        }
                    });

                    if (response.data.response && response.data.attributes) {
                        // Преобразуем данные API в массив строк для select
                        const maritalStatuses = response.data.attributes.map(status => status.title);
                        setMaritalStatusApiOptions(maritalStatuses);
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
                    } else if (error.request) {
                        setMaritalStatusError('Ошибка соединения с сервером');
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

            // Создаем переменные для условных стилей
            const isMarried = selectedMaritalStatus === 'Женат/Замужем';
            const spouseTableStyle = {
                opacity: isMarried ? 1 : 0,
                maxHeight: isMarried ? '500px' : '0',
                overflow: 'hidden',
                transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                display: isMarried ? 'block' : 'none'
            };

            const toggleBlockStyle = {
                width: '100%'
            };

            const tableContainerStyle = {
                opacity: 1,
                transform: 'translateY(0)',
                maxHeight: '216px',
                transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
            };

            const flexColumnStyle = {
                marginTop: '50px'
            };

            const radioContainerStyle = {
                marginTop: 0, 
                fontSize: '18px'
            };

            // Компонент таблицы супруга
            const SpouseTable = () => (
                React.createElement('div', {
                    className: "formRow",
                    style: spouseTableStyle
                },
                    React.createElement('table', { className: "inputTable" },
                        React.createElement('caption', { className: "tableLabel" }, "Данные супруга(-и)"),
                        React.createElement('tr', null,
                            React.createElement('td', { colSpan: "2" },
                                React.createElement('input', {
                                    type: "text",
                                    name: "FIOSuprug",
                                    placeholder: "ФИО супруга(-и)"
                                })
                            )
                        ),
                        React.createElement('tr', null,
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "text",
                                    name: "dateOfBirthTable",
                                    placeholder: "Дата рождения"
                                })
                            ),
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "tel",
                                    name: "phoneNumberTable",
                                    placeholder: "Телефон"
                                })
                            )
                        ),
                        React.createElement('tr', null,
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "text",
                                    name: "placeOfStudy",
                                    placeholder: "Место учебы/работы, рабочий телефон"
                                })
                            ),
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "text",
                                    name: "placeOfLiving",
                                    placeholder: "Место проживания"
                                })
                            )
                        )
                    )
                )
            );

            // Компонент таблицы члена семьи
            const RelativeTable = ({ index }) => (
                React.createElement('div', {
                    className: "formRow table-container",
                    style: tableContainerStyle
                },
                    React.createElement('table', { className: "inputTable" },
                        React.createElement('caption', { className: "tableLabel" }, "Данные члена семьи"),
                        React.createElement('tr', null,
                            React.createElement('td', { colSpan: "2" },
                                React.createElement('input', {
                                    type: "text",
                                    name: `FIORelative${index}`,
                                    placeholder: "Степень родства, ФИО члена семьи"
                                })
                            )
                        ),
                        React.createElement('tr', null,
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "text",
                                    name: `dateOfBirthRelative${index}`,
                                    placeholder: "Дата рождения"
                                })
                            ),
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "tel",
                                    name: `phoneNumberRelative${index}`,
                                    placeholder: "Телефон"
                                })
                            )
                        ),
                        React.createElement('tr', null,
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "text",
                                    name: `placeOfStudyRelative${index}`,
                                    placeholder: "Место учебы/работы, рабочий телефон"
                                })
                            ),
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "text",
                                    name: `placeOfLivingRelative${index}`,
                                    placeholder: "Место проживания"
                                })
                            )
                        )
                    )
                )
            );

            // Компонент таблицы ребенка
            const ChildrenTable = ({ index }) => (
                React.createElement('div', {
                    className: "formRow table-container",
                    style: tableContainerStyle
                },
                    React.createElement('table', { className: "inputTable" },
                        React.createElement('caption', { className: "tableLabel" }, "Данные совершеннолетнего ребенка"),
                        React.createElement('tr', null,
                            React.createElement('td', { colSpan: "2" },
                                React.createElement('input', {
                                    type: "text",
                                    name: `FIOChildren${index}`,
                                    placeholder: "ФИО ребенка"
                                })
                            )
                        ),
                        React.createElement('tr', null,
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "text",
                                    name: `dateOfBirthChildren${index}`,
                                    placeholder: "Дата рождения"
                                })
                            ),
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "tel",
                                    name: `phoneNumberChildren${index}`,
                                    placeholder: "Телефон"
                                })
                            )
                        ),
                        React.createElement('tr', null,
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "text",
                                    name: `placeOfStudyChildren${index}`,
                                    placeholder: "Место учебы/работы, рабочий телефон"
                                })
                            ),
                            React.createElement('td', null,
                                React.createElement('input', {
                                    type: "text",
                                    name: `placeOfLivingChildren${index}`,
                                    placeholder: "Место проживания"
                                })
                            )
                        )
                    )
                )
            );

            // Компонент кастомного select
            const CustomSelect = ({ options, placeholder, value, show, onToggle, onSelect, isLoading, error }) => (
                React.createElement('div', { className: "custom-select", style: { width: '100%' } },
                    React.createElement('div', {
                        className: `select-selected ${show ? 'select-arrow-active' : ''}`,
                        onClick: (e) => {
                            e.stopPropagation();
                            if (!isLoading) {
                                onToggle();
                            }
                        },
                        style: {
                            opacity: isLoading ? 0.6 : 1,
                            cursor: isLoading ? 'not-allowed' : 'pointer'
                        }
                    }, isLoading ? 'Загрузка...' : (error ? 'Ошибка загрузки' : (value || placeholder))),
                    !isLoading && !error && React.createElement('div', {
                        className: `select-items ${!show ? 'select-hide' : ''}`
                    }, options.map((option, index) => 
                        React.createElement('div', {
                            key: index,
                            className: value === option ? 'same-as-selected' : '',
                            onClick: () => onSelect(option)
                        }, option)
                    ))
                )
            );

            return (
                React.createElement(React.Fragment, null,
                    React.createElement('header', null,
                        React.createElement('img', {
                            src: "img/Logo с текстом.png",
                            alt: "Картинка с логотипом агенства и подписью Поиск метров"
                        })
                    ),

                    React.createElement('article', null,
                        React.createElement('h1', null, "Анкета кандидата"),
                        React.createElement('p', null, "Заполните анкету, чтобы подать заявку на вакансию")
                    ),

                    React.createElement('main', null,
                        React.createElement('section', null,
                            React.createElement('div', { className: "center-card big" },
                                React.createElement('h1', null, "Общие сведения"),
                                React.createElement('p', null, "Мы не передаём эти данные третьим лицам и используем их только для целей адаптации и сопровождения кандидатов"),

                                React.createElement('div', { className: "formRow" },
                                    React.createElement('div', { className: "input-container" },
                                        React.createElement('label', {
                                            htmlFor: "Vacancy",
                                            className: "formLabel"
                                        }, "Вакансия"),
                                        React.createElement(CustomSelect, {
                                            options: vacancyOptions,
                                            placeholder: "Выберите вакансию, на которую подаетесь",
                                            value: selectedVacancy,
                                            show: showVacancyOptions,
                                            isLoading: isLoadingVacancies,
                                            error: vacancyError,
                                            onToggle: () => {
                                                setShowVacancyOptions(!showVacancyOptions);
                                                setShowMaritalOptions(false);
                                            },
                                            onSelect: (option) => {
                                                setSelectedVacancy(option);
                                                setShowVacancyOptions(false);
                                            }
                                        }),
                                        vacancyError && React.createElement('div', {
                                            className: "error-message",
                                            style: { marginTop: '5px', fontSize: '14px', color: '#e74c3c' }
                                        }, vacancyError,
                                            React.createElement('button', {
                                                onClick: loadVacancies,
                                                style: {
                                                    marginLeft: '10px',
                                                    background: 'none',
                                                    border: 'none',
                                                    color: '#3498db',
                                                    cursor: 'pointer',
                                                    textDecoration: 'underline'
                                                }
                                            }, "Повторить")
                                        )
                                    )
                                ),

                                React.createElement('div', { className: "formRow" },
                                    React.createElement('div', { className: "input-container" },
                                        React.createElement('label', {
                                            htmlFor: "FIO",
                                            className: "formLabel"
                                        }, "ФИО"),
                                        React.createElement('input', {
                                            type: "text",
                                            name: "FIO",
                                            className: "formInput big",
                                            placeholder: "Иванов Иван Иванович"
                                        })
                                    )
                                ),

                                React.createElement('div', { className: "formRow justify-flex-start" },
                                    React.createElement('div', { className: "input-container big" },
                                        React.createElement('label', { className: "custom-radio" },
                                            React.createElement('input', {
                                                type: "radio",
                                                name: "surnameChanged",
                                                checked: surnameChanged,
                                                onChange: () => setSurnameChanged(true)
                                            }),
                                            React.createElement('span', { className: "radiomark" }),
                                            "Я менял(-а) фамилию"
                                        ),
                                        React.createElement('label', { className: "custom-radio" },
                                            React.createElement('input', {
                                                type: "radio",
                                                name: "surnameChanged",
                                                checked: !surnameChanged,
                                                onChange: () => setSurnameChanged(false)
                                            }),
                                            React.createElement('span', { className: "radiomark" }),
                                            "Я не менял(-а) фамилию"
                                        )
                                    )
                                ),

                                surnameChanged && React.createElement('div', {
                                    className: "toggle-block",
                                    style: toggleBlockStyle
                                },
                                    React.createElement('div', { className: "formRow" },
                                        React.createElement('div', { className: "input-container" },
                                            React.createElement('label', {
                                                htmlFor: "reasonOfChange",
                                                className: "formLabel"
                                            }, "Причина изменения фамилии"),
                                            React.createElement('input', {
                                                type: "text",
                                                name: "reasonOfChange",
                                                className: "formInput big",
                                                placeholder: "Опишите, почему поменяли фамилию"
                                            })
                                        )
                                    )
                                ),

                                React.createElement('div', { className: "formRow justify-space-between" },
                                    React.createElement('div', { className: "input-container w-49" },
                                        React.createElement('label', {
                                            htmlFor: "birthDate",
                                            className: "formLabel"
                                        }, "Дата рождения"),
                                        React.createElement('input', {
                                            style: { width: '100%' },
                                            type: "text",
                                            name: "birthDate",
                                            className: "formInput",
                                            placeholder: "01.01.1990"
                                        })
                                    ),
                                    React.createElement('div', { className: "input-container w-49" },
                                        React.createElement('label', {
                                            htmlFor: "birthPlace",
                                            className: "formLabel"
                                        }, "Место рождения"),
                                        React.createElement('input', {
                                            style: { width: '100%' },
                                            type: "text",
                                            name: "birthPlace",
                                            className: "formInput",
                                            placeholder: "Страна и город"
                                        })
                                    )
                                ),

                                // Продолжение формы...
                                React.createElement('div', { className: "formRow flex-direction-column", style: flexColumnStyle },
                                    React.createElement('h3', null, "Состав семьи"),
                                    React.createElement('h4', null, "Заполните эти данные, чтобы мы могли предложить вам подходящие условия")
                                ),

                                React.createElement('div', { className: "formRow" },
                                    React.createElement('div', { className: "input-container" },
                                        React.createElement('label', {
                                            htmlFor: "maritalStatus",
                                            className: "formLabel"
                                        }, "Семейное положение"),
                                        React.createElement(CustomSelect, {
                                            options: maritalStatusOptions,
                                            placeholder: "Выберите ваше семейное положение",
                                            value: selectedMaritalStatus,
                                            show: showMaritalOptions,
                                            isLoading: isLoadingMaritalStatuses,
                                            error: maritalStatusError,
                                            onToggle: () => {
                                                setShowMaritalOptions(!showMaritalOptions);
                                                setShowVacancyOptions(false);
                                            },
                                            onSelect: (option) => {
                                                setSelectedMaritalStatus(option);
                                                setShowMaritalOptions(false);
                                            }
                                        }),
                                        maritalStatusError && React.createElement('div', {
                                            className: "error-message",
                                            style: { marginTop: '5px', fontSize: '14px', color: '#e74c3c' }
                                        }, maritalStatusError,
                                            React.createElement('button', {
                                                onClick: loadMaritalStatuses,
                                                style: {
                                                    marginLeft: '10px',
                                                    background: 'none',
                                                    border: 'none',
                                                    color: '#3498db',
                                                    cursor: 'pointer',
                                                    textDecoration: 'underline'
                                                }
                                            }, "Повторить")
                                        )
                                    )
                                ),

                                React.createElement(SpouseTable),

                                React.createElement('div', { className: "formRow flex-direction-column" },
                                    React.createElement('h3', null, "1. Дети старше 18 лет")
                                ),

                                React.createElement('div', { className: "formRow justify-flex-start" },
                                    React.createElement('div', { className: "input-container big" },
                                        React.createElement('label', { className: "custom-radio" },
                                            React.createElement('input', {
                                                type: "radio",
                                                name: "haveChildren",
                                                checked: haveChildren,
                                                onChange: () => setHaveChildren(true)
                                            }),
                                            React.createElement('span', { className: "radiomark" }),
                                            "У меня есть дети старше 18 лет"
                                        ),
                                        React.createElement('label', { className: "custom-radio" },
                                            React.createElement('input', {
                                                type: "radio",
                                                name: "haveChildren",
                                                checked: !haveChildren,
                                                onChange: () => setHaveChildren(false)
                                            }),
                                            React.createElement('span', { className: "radiomark" }),
                                            "У меня нет детей старше 18 лет"
                                        )
                                    )
                                ),

                                haveChildren && React.createElement('div', {
                                    className: "toggle-block",
                                    style: toggleBlockStyle
                                },
                                    React.createElement(ChildrenTable, { index: 1 }),
                                    additionalChildrenTables.map(index =>
                                        React.createElement(ChildrenTable, { key: index, index: index })
                                    ),
                                    React.createElement('div', {
                                        className: "formRow",
                                        style: { marginBottom: 0 }
                                    },
                                        React.createElement('button', {
                                            className: "bigFormButton",
                                            onClick: addChildrenTable
                                        },
                                            React.createElement('div', { className: "textCont" }),
                                            React.createElement('svg', {
                                                viewBox: "0 0 24 24",
                                                fill: "none",
                                                xmlns: "http://www.w3.org/2000/svg"
                                            },
                                                React.createElement('path', {
                                                    d: "M12 5V19M5 12H19",
                                                    stroke: "currentColor",
                                                    strokeWidth: "2",
                                                    strokeLinecap: "round",
                                                    strokeLinejoin: "round"
                                                })
                                            ),
                                            "Добавить совершеннолетнего ребенка"
                                        )
                                    )
                                ),

                                React.createElement('div', { className: "checkboxRow", style: { maxWidth: 'none', alignItems: 'center' } },
                                    React.createElement('label', { className: "custom-checkbox", htmlFor: "personalData" },
                                        React.createElement('input', {
                                            type: "checkbox",
                                            name: "personalData",
                                            id: "personalData",
                                            checked: personalDataChecked,
                                            onChange: (e) => setPersonalDataChecked(e.target.checked)
                                        }),
                                        React.createElement('span', { className: "checkmark" })
                                    ),
                                    React.createElement('label', { htmlFor: "personalData" },
                                        "Я даю согласие на обработку ",
                                        React.createElement('span', null, "своих персональных данных")
                                    )
                                ),

                                React.createElement('div', { className: "formRow", style: { marginTop: '0px' } },
                                    React.createElement('button', {
                                        className: personalDataChecked ? "formBtn btn-active" : "formBtn btn-inactive",
                                        disabled: !personalDataChecked
                                    }, "Отправить анкету")
                                )
                            )
                        )
                    )
                )
            );
        }

        // Главное приложение
        function App() {
            const [currentPage, setCurrentPage] = useState('candidate');

            return React.createElement('div', null,
                currentPage === 'candidate' && React.createElement(CandidateForm)
            );
        }

        // Монтируем главное приложение
        ReactDOM.render(React.createElement(App), document.getElementById('root'));
        <?php echo '@endverbatim'; ?>
    </script>
</body>
</html>