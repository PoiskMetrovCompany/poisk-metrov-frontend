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
                {currentView === 'table' ? (
                    <>
                        <Header />
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
                ) : currentView === 'form' ? (
                    <ShowForm
                        vacancyKey={selectedCandidate}
                        onBackToTable={handleBackToTable}
                    />
                ) : (
                    <CandidatesSettings />
                )}
            </>
        );
    }

    // Монтируем главное приложение
    ReactDOM.render(React.createElement(App), document.getElementById('root'));
    <?php echo '@endverbatim'; ?>
</script>
</body>
</html>
