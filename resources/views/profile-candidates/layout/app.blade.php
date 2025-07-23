<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система регистрации</title>
    <link rel="stylesheet" href="css/style.css">
    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://unpkg.com/imask"></script>
</head>
<body>
    <div id="root"></div>

    

    <script type="text/babel">
        @verbatim
         // Компонент регистрации кандидата
          function CandidateRegForm() {
            const [isCodeMode, setIsCodeMode] = useState(false);
            const [isPhoneValidated, setIsPhoneValidated] = useState(false);
            const [timeLeft, setTimeLeft] = useState(0);
            const [phoneValue, setPhoneValue] = useState('');
            const [isCheckboxChecked, setIsCheckboxChecked] = useState(false);
            const [showCheckmark, setShowCheckmark] = useState(false);
            
            const phoneInputRef = useRef(null);
            const currentMaskRef = useRef(null);
            const timerIntervalRef = useRef(null);

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
                        mask: ' 0 0 0 0 ',
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
                    return isPhoneValid && isCheckboxChecked;
                }
                return false;
            };

            const checkCode = (value) => {
                const enteredCode = value.replace(/\s/g, '').replace(/_/g, '');
                if (enteredCode === '1234') {
                    setShowCheckmark(true);
                    console.log('Код введен правильно!');
                } else {
                    setShowCheckmark(false);
                }
            };

            const handleInputChange = (e) => {
                const value = e.target.value;
                setPhoneValue(value);
                
                if (isCodeMode) {
                    checkCode(value);
                }
            };

            const handleCheckboxChange = (e) => {
                setIsCheckboxChecked(e.target.checked);
            };

            const startTimer = () => {
                setTimeLeft(60);
                
                if (timerIntervalRef.current) {
                    clearInterval(timerIntervalRef.current);
                }

                timerIntervalRef.current = setInterval(() => {
                    setTimeLeft(prev => {
                        if (prev <= 1) {
                            clearInterval(timerIntervalRef.current);
                            return 0;
                        }
                        return prev - 1;
                    });
                }, 1000);
            };

            const handleGetCodeClick = (e) => {
                e.preventDefault();
                
                if (!isCodeMode) {
                    startTimer();
                    setIsCodeMode(true);
                    setPhoneValue('');
                    setShowCheckmark(false);
                } else {
                    startTimer();
                    setPhoneValue('');
                    setShowCheckmark(false);
                }
            };

            const handleChangeNumber = (e) => {
                e.preventDefault();
                
                setIsCodeMode(false);
                
                if (timerIntervalRef.current) {
                    clearInterval(timerIntervalRef.current);
                }
                
                setPhoneValue('');
                setShowCheckmark(false);
                
                setTimeout(() => {
                    if (phoneInputRef.current) {
                        phoneInputRef.current.focus();
                    }
                }, 0);
            };

            const getButtonText = () => {
                if (timeLeft > 0) {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    return `Получить код повторно ${timeString}`;
                }
                return isCodeMode ? "Получить код повторно" : "Получить код";
            };

            const getButtonClass = () => {
                if (timeLeft > 0) {
                    return "formBtn btn-inactive";
                }
                if (!isCodeMode) {
                    return checkButtonState() ? "formBtn btn-active" : "formBtn btn-inactive";
                }
                return "formBtn btn-active";
            };

            const isButtonDisabled = () => {
                if (timeLeft > 0) return true;
                if (!isCodeMode) return !checkButtonState();
                return false;
            };

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
                                        />
                                        {showCheckmark && (
                                            <div className="checkmark-icon" id="checkmarkIcon">
                                                <svg viewBox="0 0 24 24">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                </svg>
                                            </div>
                                        )}
                                    </div>

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
                                        // 
                                    >
                                        <label className="custom-checkbox" htmlFor="personalData">
                                            <input 
                                                type="checkbox" 
                                                name="personalData" 
                                                id="personalData"
                                                checked={isCheckboxChecked}
                                                onChange={handleCheckboxChange}
                                            />
                                            <span className="checkmark"></span>
                                        </label>
                                        <label htmlFor="personalData">
                                            Я даю согласие на обработку <span>своих персональных данных</span>
                                        </label>
                                    </div>
                                </form>
                                
                                <a 
                                    href="#" 
                                    
                                    id="changeNumber"
                                    onClick={handleChangeNumber}
                                >
                                    Изменить номер
                                </a>
                            </div>
                        </section>
                    </main>
                </>
            );
        }
        const { useState, useEffect, useRef } = React;
            // Главное приложение с роутингом
        function App() {
            const [currentPage, setCurrentPage] = useState('candidate'); // 'candidate', 'security', 'showForm', 'candidatesSettings', или 'candidates'

            const navigateToCandidate = () => {
                setCurrentPage('candidate');
                window.history.pushState({}, '', '/candidate');
            };

            const navigateToSecurity = () => {
                setCurrentPage('security');
                window.history.pushState({}, '', '/security');
            };

            const navigateToShowForm = () => {
                setCurrentPage('showForm');
                window.history.pushState({}, '', '/showForm');
            };

            const navigateToCandidatesSettings = () => {
                setCurrentPage('candidatesSettings');
                window.history.pushState({}, '', '/candidatesSettings');
            };

            const navigateToCandidates = () => {
                setCurrentPage('candidates');
                window.history.pushState({}, '', '/candidates');
            };

            // Обработка браузерной навигации
            useEffect(() => {
                const handlePopState = () => {
                    const path = window.location.pathname;
                    if (path === '/security') {
                        setCurrentPage('security');
                    } else if (path === '/showForm') {
                        setCurrentPage('showForm');
                    } else if (path === '/candidatesSettings') {
                        setCurrentPage('candidatesSettings');
                    } else if (path === '/candidates') {
                        setCurrentPage('candidates');
                    } else {
                        setCurrentPage('candidate');
                    }
                };

                window.addEventListener('popstate', handlePopState);
                
                // Устанавливаем начальную страницу по URL
                const initialPath = window.location.pathname;
                if (initialPath === '/security') {
                    setCurrentPage('security');
                } else if (initialPath === '/showForm') {
                    setCurrentPage('showForm');
                } else if (initialPath === '/candidatesSettings') {
                    setCurrentPage('candidatesSettings');
                } else if (initialPath === '/candidates') {
                    setCurrentPage('candidates');
                }

                return () => {
                    window.removeEventListener('popstate', handlePopState);
                };
            }, []);

            return (
                <div>
                    {/* Навигация - можно скрыть или стилизовать по необходимости */}
                    <nav style={{padding: '10px', background: '#f0f0f0', display: 'none'}}>
                        <button onClick={navigateToCandidate}>Регистрация кандидата</button>
                        <button onClick={navigateToSecurity}>Вход администратора</button>
                        <button onClick={navigateToShowForm}>Показать форму</button>
                        <button onClick={navigateToCandidatesSettings}>Настройки кандидатов</button>
                        <button onClick={navigateToCandidates}>Кандидаты</button>
                    </nav>

                    {currentPage === 'candidate' && <CandidateRegForm />}
                </div>
            );
        }

        // Монтируем главное приложение
        ReactDOM.render(<App />, document.getElementById('root'));
        @verbatim
    </script>
</body>
</html>











