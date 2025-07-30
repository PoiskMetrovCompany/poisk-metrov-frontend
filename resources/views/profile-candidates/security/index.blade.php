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

<style>



    a{
        text-decoration: none;
        color: rgba(129, 129, 129, 1);
    }
   .status-new {
        color: #C5801F !important;
        background: #F8F8E8 !important;
    }
    .status-checked {
        color: rgba(15, 141, 19, 1) !important;
        background: rgba(228, 248, 228, 1) !important;
    }
    .status-needRevision {
        color: rgba(44, 129, 150, 1) !important;
        background: rgba(236, 246, 248, 1) !important;
    }
    .status-rejected {
        color: rgba(240, 76, 26, 1) !important;
        background: rgba(255, 239, 233, 1) !important;
    }

    /* Стили для выбранных элементов в зависимости от статуса */
    .status-new .select-option.selected {
        color: #C5801F !important;
        font-weight: 500;
    }
    .status-checked .select-option.selected {
        color: rgba(15, 141, 19, 1) !important;
        font-weight: 500;
    }
    .status-needRevision .select-option.selected {
        color: rgba(44, 129, 150, 1) !important;
        font-weight: 500;
    }
    .status-rejected .select-option.selected {
        color: rgba(240, 76, 26, 1) !important;
        font-weight: 500;
    }
</style>

<body>
<div id="root"></div>



<script type="text/babel">
    <?php echo '@verbatim'; ?>
    const { useState, useEffect, useRef } = React;


function Header() {
  const [showNotifications, setShowNotifications] = useState(false);
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(false);
  const notificationButtonRef = useRef(null);

  // Функция для форматирования даты
  const formatNotificationDate = (dateString) => {
    const notificationDate = new Date(dateString);
    const now = new Date();
    
    // Проверяем, является ли дата сегодняшней
    const isToday = notificationDate.toDateString() === now.toDateString();
    
    const hours = notificationDate.getHours().toString().padStart(2, '0');
    const minutes = notificationDate.getMinutes().toString().padStart(2, '0');
    const time = `${hours}:${minutes}`;
    
    if (isToday) {
      return time;
    } else {
      const day = notificationDate.getDate().toString().padStart(2, '0');
      const month = (notificationDate.getMonth() + 1).toString().padStart(2, '0');
      return `${time} ${day}.${month}`;
    }
  };

  // Добавляем useEffect для запроса при монтировании
  useEffect(() => {
    const fetchNotifications = async () => {
      setLoading(true);
      const url = '/api/v1/notification/new-candidates';
      
      // Используем уже существующую в проекте функцию для получения токена
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

      // Функция для получения CSRF токена (также как в проекте)
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

      const token = getAccessToken();
      const csrfToken = getCsrfToken();
      
      console.log('🚀 Отправляем GET запрос на:', url);
      console.log('🌐 Текущий домен:', window.location.origin);
      console.log('📍 Полный URL:', window.location.origin + url);
      console.log('🔑 Access Token найден:', token ? 'Да' : 'Нет');
      console.log('🔑 Access Token (первые 20 символов):', token ? token.substring(0, 20) + '...' : 'Отсутствует');
      console.log('🛡️ CSRF Token найден:', csrfToken ? 'Да' : 'Нет');

      try {
        const headers = {
          'accept': 'application/json',
          'Content-Type': 'application/json'
        };

        // Добавляем Authorization заголовок, если токен найден
        if (token) {
          headers['Authorization'] = `Bearer ${token}`;
        }

        // Добавляем CSRF токен
        if (csrfToken) {
          headers['X-CSRF-TOKEN'] = csrfToken;
        }

        console.log('📋 Заголовки запроса:', headers);

        const response = await fetch(url, {
          method: 'GET',
          headers: headers
        });
        
        console.log('📡 Статус ответа:', response.status);
        console.log('📋 Заголовки ответа:', Object.fromEntries(response.headers.entries()));
        console.log('✅ Запрос успешен:', response.ok);

        if (response.ok) {
          // Получаем JSON ответ
          const data = await response.json();
          console.log('✅ Данные уведомлений получены:', data);
          
          // Обновляем состояние с полученными уведомлениями
          if (data.response && data.attributes) {
            setNotifications(data.attributes);
          }
        } else {
          // Получаем текст ошибки
          const errorText = await response.text();
          console.error('❌ Ошибка ответа сервера:', {
            status: response.status,
            statusText: response.statusText,
            errorText: errorText.substring(0, 200)
          });
        }

      } catch (error) {
        console.error('❌ Ошибка при получении уведомлений:', error);
        console.error('🔍 Детали ошибки:', {
          name: error.name,
          message: error.message,
          stack: error.stack
        });
      } finally {
        setLoading(false);
      }
    };

    fetchNotifications();
  }, []); // Пустой массив зависимостей означает, что эффект выполнится только при монтировании

  const handleLogout = () => {
    document.cookie = 'access_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    window.location.reload();
  };

  const toggleNotifications = () => {
    setShowNotifications(!showNotifications);
  };

  // Определяем какую иконку показывать
  const getNotificationIcon = () => {
    return notifications.length > 0 ? '/img/ringActive.png' : '/img/ring.png';
  };

  const getNotificationAlt = () => {
    return notifications.length > 0 ? 'Есть новые уведомления' : 'Уведомлений нет';
  };

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
          <a href="/profile-candidates/security/" className="active">Кандидаты</a>
          <a href="/profile-candidates/security/settings">Настройки</a>
        </div>
        <div style={{display: 'flex', justifyContent: 'space-between', minWidth: '250px', position: 'relative'}}>
          {/* Контейнер для кнопки уведомлений и блока уведомлений */}
          <div style={{position: 'relative'}}>
            <button
              id="notifBtn"
              ref={notificationButtonRef}
              onClick={toggleNotifications}
              style={{position: 'relative'}}
            >
              <img 
                src={getNotificationIcon()} 
                alt={getNotificationAlt()} 
              />
            </button>
            {/* Блок уведомлений */}
            {showNotifications && (
              <div className="notifications-container">
                <div className="header">
                  <h1 className="notification-header">Уведомления</h1>
                </div>
                <div className="divider"></div>
                <div className="notifications-list">
                  {loading ? (
                    <div className="notification-item">
                      <div className="notification-content">
                        <span className="notification-text">Загрузка уведомлений...</span>
                      </div>
                    </div>
                  ) : notifications.length > 0 ? (
                    notifications.map((notification, index) => (
                      <div key={notification.id?.$oid || index} className="notification-item">
                        <div className="notification-dot"></div>
                        <div className="notification-content">
                          <span className="notification-text">{notification.title}</span>
                          <span className="notification-time">
                            {formatNotificationDate(notification.created_at)}
                          </span>
                        </div>
                      </div>
                    ))
                  ) : (
                    <div className="notification-item">
                      <div className="notification-content">
                        <span className="notification-text">Уведомлений нет</span>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            )}
          </div>
          <button id="exitBtn" onClick={handleLogout}>
            Выйти из ЛК <img src="/img/arowRight.png" alt="Стрелочка вправо" />
          </button>
        </div>
      </div>
    </header>
  );
}
 function ShowForm({ vacancyKey, setSelectedVacancyKey }) {
    const handleLogout = () => {
        document.cookie = 'access_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        window.location.reload();
    };

    const [isSelectOpen, setIsSelectOpen] = useState(false);
    const [selectedOption, setSelectedOption] = useState({
        value: 'new',
        text: 'Новая анкета'
    });
    const [commentValue, setCommentValue] = useState('');
    const [isUpdating, setIsUpdating] = useState(false);
    
    // Новые состояния для данных кандидата
    const [candidateData, setCandidateData] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState('');

    const selectOptions = [
        { value: 'new', text: 'Новая анкета' },
        { value: 'needs-work', text: 'Нужна доработка' },
        { value: 'checked', text: 'Проверен' },
        { value: 'rejected', text: 'Отклонен' }
    ];

    // Функция для получения CSRF токена
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

    // Функция для загрузки данных кандидата
    const fetchCandidateData = async () => {
        if (!vacancyKey) {
            setError('Ключ кандидата не передан');
            setIsLoading(false);
            return;
        }

        const accessToken = getAccessToken();
        if (!accessToken) {
            setError('Access token не найден в cookies');
            setIsLoading(false);
            return;
        }

        setIsLoading(true);
        setError('');

        try {
            const csrfToken = getCsrfToken();
            
            const headers = {
                'accept': 'application/json',
                'Authorization': `Bearer ${accessToken}`,
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            };

            console.log('=== ЗАПРОС ДАННЫХ КАНДИДАТА ===');
            console.log('vacancyKey:', vacancyKey);
            console.log('URL:', `http://127.0.0.1:8000/api/v1/candidates/read?key=${vacancyKey}`);

            const response = await fetch(`http://127.0.0.1:8000/api/v1/candidates/read?key=${vacancyKey}`, {
                method: 'GET',
                headers: headers
            });

            console.log('=== ОТВЕТ СЕРВЕРА ===');
            console.log('Статус ответа:', response.status);

            if (response.ok) {
                const result = await response.json();
                console.log('✅ Данные кандидата получены:', result);
                
                if (result.response && result.attributes) {
                    setCandidateData(result.attributes);
                    
                    // Устанавливаем текущий статус в селектор
                    const currentStatus = result.attributes.status;
                    const statusOption = selectOptions.find(option => {
                        const statusMap = {
                            'new': 'Новая анкета',
                            'needs-work': 'Нужна доработка',
                            'checked': 'Проверен',
                            'rejected': 'Отклонен'
                        };
                        return statusMap[option.value] === currentStatus;
                    });
                    
                    if (statusOption) {
                        setSelectedOption(statusOption);
                    }
                    
                    // Устанавливаем комментарий, если есть
                    if (result.attributes.comment) {
                        setCommentValue(result.attributes.comment);
                    }
                } else {
                    setError('Неверный формат ответа сервера');
                }
            } else {
                const errorText = await response.text();
                console.error('❌ Ошибка при получении данных кандидата:', response.status, errorText);
                setError(`Ошибка ${response.status}: ${errorText}`);
            }
        } catch (error) {
            console.error('=== ОШИБКА ЗАПРОСА ===');
            console.error('Ошибка:', error);
            setError(`Ошибка сети: ${error.message}`);
        } finally {
            setIsLoading(false);
        }
    };

    // Загружаем данные при монтировании компонента
    useEffect(() => {
        fetchCandidateData();
    }, [vacancyKey]);

    // Функция для преобразования значения статуса в точно такой же формат как в API
    const mapStatusForAPI = (statusValue) => {
        const statusMap = {
            'new': 'Новая анкета',
            'needs-work': 'Нужна доработка',
            'checked': 'Проверен',
            'rejected': 'Отклонен'
        };
        return statusMap[statusValue] || statusValue;
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
        console.log('requestData:', requestData);

        try {
            const csrfToken = getCsrfToken();
            const headers = {
                'accept': 'application/json',
                'Authorization': `Bearer ${accessToken}`,
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            };

            const response = await fetch('http://127.0.0.1:8000/api/v1/candidates/update', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(requestData)
            });

            if (response.ok) {
                const result = await response.json();
                console.log('✅ Статус успешно обновлен:', result);
                return true;
            } else {
                const errorText = await response.text();
                console.error('❌ Ошибка при обновлении статуса:', response.status, errorText);
                return false;
            }
        } catch (error) {
            console.error('=== ОШИБКА ЗАПРОСА ===');
            console.error('Ошибка:', error);
            return false;
        } finally {
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

        try {
            const csrfToken = getCsrfToken();
            const headers = {
                'accept': 'application/json',
                'Authorization': `Bearer ${accessToken}`,
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            };

            const response = await fetch('http://127.0.0.1:8000/api/v1/candidates/update', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(requestData)
            });

            if (response.ok) {
                const result = await response.json();
                console.log('✅ Комментарий успешно отправлен:', result);
                setCommentValue('');
            } else {
                const errorText = await response.text();
                console.error('❌ Ошибка при отправке комментария:', response.status, errorText);
            }
        } catch (error) {
            console.error('=== ОШИБКА ОТПРАВКИ КОММЕНТАРИЯ ===');
            console.error('Ошибка:', error);
        }
    };

    const getStatusClass = (statusText) => {
        console.log(statusText + " СТАТУС");
    switch (statusText) {
        case 'Новая анкета':
        return 'status-new';
        case 'Проверен':
        return 'status-checked';
        case 'Нужна доработка':
        return 'status-needRevision';
        case 'Отклонен':
        return 'status-rejected';
        default:
        return 'status-new'; // по умолчанию
    }
    };


    // Функции для форматирования данных
    const formatDate = (dateString) => {
        if (!dateString) return '';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('ru-RU');
        } catch {
            return dateString;
        }
    };

    const formatPhone = (phone) => {
        if (!phone) return '';
        return phone;
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

    // Показываем загрузку
    if (isLoading) {
        return (
            <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', minHeight: '400px' }}>
                <p>Загрузка данных кандидата...</p>
            </div>
        );
    }

    // Показываем ошибку
    if (error) {
        return (
            <div style={{ display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', minHeight: '400px' }}>
                <p style={{ color: 'red', marginBottom: '20px' }}>Ошибка: {error}</p>
                <button onClick={() => setSelectedVacancyKey(null)}>Вернуться к списку</button>
            </div>
        );
    }

    // Показываем сообщение, если данные не загружены
    if (!candidateData) {
        return (
            <div style={{ display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', minHeight: '400px' }}>
                <p>Данные кандидата не найдены</p>
                <button onClick={() => setSelectedVacancyKey(null)}>Вернуться к списку</button>
            </div>
        );
    }

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
                        <span 
                            style={{cursor: 'pointer'}} 
                            onClick={() => setSelectedVacancyKey(null)}
                            className="active"
                        >
                            Кандидаты
                        </span>
                        <a href="/profile-candidates/security/settings">Настройки</a>
                    </div>
                    <div style={{display: 'flex', justifyContent: 'space-between', minWidth: '250px'}}>
                        <button id="notifBtn"><img src="/img/ring.png" alt="Уведомлений нет" /></button>
                        <button id="exitBtn" onClick={handleLogout}>Выйти из ЛК <img src="/img/arowRight.png" alt="Стрелочка вправо" /></button>
                    </div>
                </div>
            </header>
            <main style={{marginTop: '5rem'}}>
                <section>
                    <div className="center-card big">
                       <div className="fixedMenu">
                            <div className="navArea">
                                <div 
                                className={`yellowSelect ${isSelectOpen ? 'open' : ''} ${isUpdating ? 'updating' : ''}`} 
                                id="customSelect"
                                >
                                <div className={`select-trigger ${getStatusClass(selectedOption.text)}`} id="selectTrigger" onClick={handleSelectToggle} style={{opacity: isUpdating ? 0.6 : 1}}>
                                    {selectedOption.text}
                                    {isUpdating && <span style={{marginLeft: '10px'}}>...</span>}
                                    <div className="trigger-icons"></div>
                                </div>
                                <div className={`select-dropdown ${getStatusClass(selectedOption.text)}`} id="selectDropdown">
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
                                <a className="#activeLink" href="#generalData">Общие сведенья</a>
                                <a href="#passportData">Паспотные данные</a>
                                <a href="#familyData">Состав семьи</a>
                                <a href="#legalData">Юридический статус</a>
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
                        <p
                            style={{position: 'absolute', top: '-2.7rem', left: '0', display: 'flex', alignItems: 'center', gap: '1rem', cursor: 'pointer'}}
                            onClick={() => setSelectedVacancyKey(null)}
                        >
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.5 3L2 7.5L6.5 12M2.5 7.5H14" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                            </svg>
                            Вернуться к списку
                        </p>

                        <div className="formRow justify-space-between" id="generalData">
                            <h3 style={{width: 'auto', display: 'flex', alignItems: 'center'}}>
                                {[
                                    `${candidateData.last_name || ''} ${candidateData.first_name || ''} ${candidateData.middle_name || ''}`.trim(),
                                    candidateData.birth_date ? `${new Date().getFullYear() - new Date(candidateData.birth_date).getFullYear()} лет` : ''
                                ].filter(Boolean).join(',    ')}
                            </h3>
                            <p>Дата подачи</p>
                        </div>
                        <span id="line"></span>
                        <div className="formRow justify-space-between">
                            <h4 style={{width: 'auto', display: 'flex', alignItems: 'center', marginTop: '0'}}>
                                {candidateData.vacancy?.attributes?.title || 'Вакансия не указана'}
                            </h4>
                            <p>{formatDate(candidateData.created_at)}</p>
                        </div>

                        {candidateData.reason_for_changing_surnames && (
                            <div id="surnameChangeReason" className="toggle-block" style={{width: '100%'}}>
                                <div className="formRow">
                                    <div className="input-container">
                                        <label htmlFor="reasonOfChange" id="formLabel" className="formLabel">Причина изменения фамилии</label>
                                        <input type="text" name="reasonOfChange" id="reasonOfChange" className="formInput big" 
                                               placeholder="Опишите, почему поменяли фамилию" 
                                               value={candidateData.reason_for_changing_surnames || ''} readOnly />
                                    </div>
                                </div>
                            </div>
                        )}

                        <div className="formRow justify-space-between">
                            <div className="input-container w-49">
                                <label htmlFor="birthDate" id="formLabel" className="formLabel">Дата рождения</label>
                                <input style={{width: '100%'}} type="text" name="birthDate" id="birthDate" className="formInput" 
                                       placeholder="01.01.1990" value={formatDate(candidateData.birth_date)} readOnly />
                            </div>
                            <div className="input-container w-49">
                                <label htmlFor="birthPlace" id="formLabel" className="formLabel">Место рождения</label>
                                <input style={{width: '100%'}} type="text" name="birthPlace" id="birthPlace" className="formInput" 
                                       placeholder="Страна и город" value={`${candidateData.country_birth || ''}, ${candidateData.city_birth || ''}`} readOnly />
                            </div>
                        </div>
                        <div className="formRow justify-space-between">
                            <div className="input-container w-49">
                                <label htmlFor="mobileNumber" id="mobileNumber" className="formLabel">Мобильный телефон</label>
                                <input style={{width: '100%'}} type="tel" name="mobileNumber" id="mobileNumber" className="formInput" 
                                       placeholder="+7(999)999-99-99" value={formatPhone(candidateData.mobile_phone_candidate)} readOnly />
                            </div>
                            <div className="input-container w-49">
                                <label htmlFor="domesticNumber" id="domesticNumber" className="formLabel">Домашний телефон</label>
                                <input style={{width: '100%'}} type="tel" name="domesticNumber" id="domesticNumber" className="formInput" 
                                       placeholder="999 999" value={formatPhone(candidateData.home_phone_candidate)} readOnly />
                            </div>
                        </div>
                        <div className="formRow justify-space-between">
                            <div className="input-container w-49">
                                <label htmlFor="email" id="email" className="formLabel">E-mail</label>
                                <input style={{width: '100%'}} type="email" name="email" id="email" className="formInput" 
                                       value={candidateData.mail_candidate || ''} placeholder="example@gmail.com" readOnly />
                            </div>
                            <div className="input-container w-49">
                                <label htmlFor="INN" id="INN" className="formLabel">ИНН</label>
                                <input style={{width: '100%'}} type="text" name="INN" id="INN" className="formInput" 
                                       value={candidateData.inn || ''} placeholder="123456789012" readOnly />
                            </div>
                        </div>

                        <div className="formRow" style={{marginTop: '50px'}} id = "passportData">
                            <h3>Паспортные данные</h3>
                        </div>

                        <div className="formRow justify-space-between">
                            <div className="input-container w-49">
                                <label htmlFor="passwordSeriaNumber" id="passwordSeriaNumber" className="formLabel">Серия и номер</label>
                                <input style={{width: '100%'}} type="text" name="passwordSeriaNumber" id="passwordSeriaNumber" className="formInput" 
                                       placeholder="1234 567890" value={`${candidateData.passport_series || ''} ${candidateData.passport_number || ''}`} readOnly />
                            </div>
                            <div className="input-container w-49">
                                <label htmlFor="dateOfIssue" id="dateOfIssue" className="formLabel">Дата выдачи</label>
                                <input style={{width: '100%'}} type="text" name="dateOfIssue" id="dateOfIssue" className="formInput" 
                                       placeholder="01.01.1990" value={formatDate(candidateData.passport_issued_date)} readOnly />
                            </div>
                        </div>
                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="issuedBy" id="issuedBy" className="formLabel">Кем выдан</label>
                                <input style={{width: '100%'}} type="text" name="issuedBy" id="issuedBy" className="formInput" 
                                       placeholder="ОФУМС России" value={candidateData.passport_issued || ''} readOnly />
                            </div>
                        </div>
                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="adressOfPermanentReg" id="adressOfPermanentReg" className="formLabel">Адрес постоянной регистрации</label>
                                <input style={{width: '100%'}} type="text" name="adressOfPermanentReg" id="adressOfPermanentReg" className="formInput" 
                                       placeholder="Адрес постоянной регистрации" value={candidateData.permanent_registration_address || ''} readOnly />
                            </div>
                        </div>
                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="adressOfTemporaryReg" id="adressOfTemporaryReg" className="formLabel">Адрес временной регистрации</label>
                                <input style={{width: '100%'}} type="text" name="adressOfTemporaryReg" id="adressOfTemporaryReg" className="formInput" 
                                       placeholder="Адрес временной регистрации" value={candidateData.temporary_registration_address || ''} readOnly />
                            </div>
                        </div>
                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="adressOfFactialLiving" id="adressOfFactialLiving" className="formLabel">Адрес фактического проживания</label>
                                <input style={{width: '100%'}} type="text" name="adressOfFactialLiving" id="adressOfFactialLiving" className="formInput" 
                                       placeholder="Адрес фактического проживания" value={candidateData.actual_residence_address || ''} readOnly />
                            </div>
                        </div>

                        <div className="formRow" style={{marginTop: '50px'}} id = "familyData">
                            <h3>Состав семьи</h3>
                        </div>
                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="maritalStatus" id="maritalStatus" className="formLabel">Семейное положение</label>
                                <input style={{width: '100%'}} type="text" name="maritalStatus" id="maritalStatus" className="formInput" 
                                       placeholder="Семейное положение" value={candidateData.marital_statuses?.attributes?.title || ''} readOnly />
                            </div>
                        </div>

                        {candidateData.family_partner && (
                            <div className="formRow">
                                <table className="inputTable showTable">
                                    <tbody>
                                    <tr>
                                        <td colSpan="2">
                                            <input type="text" name="FIOSuprug" placeholder="ФИО супруга(-и)" 
                                                   value={candidateData.family_partner.full_name || ''} readOnly />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="dateOfBirthTable" placeholder="Дата рождения" 
                                                   value={formatDate(candidateData.family_partner.birth_date)} readOnly />
                                        </td>
                                        <td>
                                            <input type="tel" name="phoneNumberTable" placeholder="Телефон" 
                                                   value={candidateData.family_partner.phone || ''} readOnly />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="placeOfStudy" placeholder="Место учебы/работы, рабочий телефон" 
                                                   value={candidateData.family_partner.work_place || ''} readOnly />
                                        </td>
                                        <td>
                                            <input type="text" name="placeOfLiving" placeholder="Место проживания" 
                                                   value={candidateData.family_partner.residence_place || ''} readOnly />
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        )}

                        {candidateData.adult_children && candidateData.adult_children.length > 0 && (
                            <>
                                <div className="formRow flex-direction-column">
                                    <h3>Данные совершеннолетнего ребенка</h3>
                                </div>
                                <div className="formRow">
                                    <div className="input-container">
                                        <label htmlFor="hasGraduatedChild" id="hasGraduatedChild" className="formLabel">Наличие совершеннолетних детей</label>
                                        <input style={{width: '100%'}} type="text" name="hasGraduatedChild" id="hasGraduatedChild" className="formInput" 
                                               value="Есть" readOnly />
                                    </div>
                                </div>
                                {candidateData.adult_children.map((child, index) => (
                                    <div key={index} id="doesHaveAdultChildren" className="toggle-block" style={{width: '100%'}}>
                                        <div className="formRow showTable">
                                            <table className="inputTable showTable">
                                                <tbody>
                                                <tr>
                                                    <td colSpan="2">
                                                        <input type="text" name={`FIOChildren${index + 1}`} placeholder="ФИО ребенка" 
                                                               value={child.full_name || ''} readOnly />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name={`dateOfBirthChildren${index + 1}`} placeholder="Дата рождения" 
                                                               value={formatDate(child.birth_date)} readOnly />
                                                    </td>
                                                    <td>
                                                        <input type="tel" name={`phoneNumberChildren${index + 1}`} placeholder="Телефон" 
                                                               value={child.phone || ''} readOnly />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name={`placeOfStudyChildren${index + 1}`} placeholder="Место учебы/работы, рабочий телефон" 
                                                               value={child.work_study_place || ''} readOnly />
                                                    </td>
                                                    <td>
                                                        <input type="text" name={`placeOfLivingChildren${index + 1}`} placeholder="Место проживания" 
                                                               value={child.residence_address || ''} readOnly />
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                ))}
                            </>
                        )}

                        {candidateData.adult_family_members && candidateData.adult_family_members.length > 0 && (
                            <>
                                <div className="formRow flex-direction-column">
                                    <h3>2. Члены семьи старше 18 лет</h3>
                                </div>

                                {candidateData.adult_family_members.map((member, index) => (
                                    <div key={index} id="doesHaveAdultRelative" className="toggle-block" style={{width: '100%'}}>
                                        <div className="formRow">
                                            <table className="inputTable showTable">
                                                <tbody>
                                                <tr>
                                                    <td colSpan="2">
                                                        <input type="text" name={`FIORelative${index + 1}`} placeholder="Степень родства, ФИО члена семьи" 
                                                               value={member.relationship_and_name} readOnly />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name={`dateOfBirthRelative${index + 1}`} placeholder="Дата рождения" 
                                                               value={formatDate(member.birth_date)} readOnly />
                                                    </td>
                                                    <td>
                                                        <input type="tel" name={`phoneNumberRelative${index + 1}`} placeholder="Телефон" 
                                                               value={member.phone || ''} readOnly />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name={`placeOfStudyRelative${index + 1}`} placeholder="Место учебы/работы, рабочий телефон" 
                                                               value={member.work_study_place || ''} readOnly />
                                                    </td>
                                                    <td>
                                                        <input type="text" name={`placeOfLivingRelative${index + 1}`} placeholder="Место проживания" 
                                                               value={member.residence_address || ''} readOnly />
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                ))}
                            </>
                        )}

                        <div className="formRow flex-direction-column" style={{marginTop: '50px'}} id = "legalData">
                            <h3>Юридический статус</h3>
                        </div>
                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="militaryDuty" id="militaryDuty" className="formLabel">Статус военнообязанного</label>
                                <input type="text" name="militaryDuty" id="militaryDuty" className="formInput big" 
                                       value={candidateData.serviceman ? 'Является военнообязанным' : 'Не является военнообязанным'} readOnly />
                            </div>
                        </div>
                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="reasonOfChange" id="formLabel" className="formLabel">Наличие уголовной или административной ответственности</label>
                                <input type="text" name="reasonOfChange" id="reasonOfChange" className="formInput big" 
                                       value={candidateData.law_breaker ? 'Да, имеется' : 'Нет'} readOnly />
                            </div>
                        </div>
                        {candidateData.law_breaker && (
                            <div className="formRow">
                                <div className="input-container">
                                    <label htmlFor="whyPrisoner" id="whyPrisoner" className="formLabel">Причины привлечения к уголовной или административной ответственности</label>
                                    <input type="text" name="whyPrisoner" id="whyPrisoner" className="formInput big" 
                                           value={candidateData.law_breaker || ''} readOnly />
                                </div>
                            </div>
                        )}
                        <div className="formRow">
                            <div className="input-container">
                                <label htmlFor="isLegalEntity" id="isLegalEntity" className="formLabel">Является или нет (со-)учредителем юридического лица</label>
                                <input type="text" name="isLegalEntity" id="isLegalEntity" className="formInput big" 
                                       value={candidateData.legal_entity !== "Нет" ? "Да" : "Нет"} readOnly />
                            </div>
                        </div>
                        {candidateData.legal_entity && candidateData.legal_entity !== 'Нет' && (
                            <div className="formRow">
                                <div className="input-container">
                                    <label htmlFor="LegalEntityActivity" id="LegalEntityActivity" className="formLabel">Вид деятельности юридического лица</label>
                                    <input type="text" name="LegalEntityActivity" id="LegalEntityActivity" className="formInput big" 
                                           value={candidateData.legal_entity || ''} readOnly />
                                </div>
                            </div>
                        )}
                    </div>
                </section>
            </main>
        </>
    );
}


function CandidatesTable({ onFiltersClick, onRowClick, filtersButtonRef, filteredData, activeFilters, onFiltersReset }) {
    const [candidates, setCandidates] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [selectedKeys, setSelectedKeys] = useState([]);
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
    const [downloadLoading, setDownloadLoading] = useState(false);
    const [isAuthorized, setIsAuthorized] = useState(false);
    // Добавляем состояние для отслеживания загрузки отдельных анкет
    const [singleDownloadLoading, setSingleDownloadLoading] = useState({});

    const getAccessToken = () => {
        const cookies = document.cookie.split(';');
        const tokenCookie = cookies.find(cookie => cookie.trim().startsWith('access_token='));
        return tokenCookie ? tokenCookie.split('=')[1] : null;
    };

    const getCsrfToken = () => {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : null;
    };

    // Проверка авторизации при монтировании компонента
    useEffect(() => {
        const token = getAccessToken();
        if (!token) {
            // Редирект на страницу авторизации
            window.location.href = '/profile-candidates/security/login';
            return;
        }
        setIsAuthorized(true);
    }, []);

    // Добавить вспомогательные функции для фильтров
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

    const getStatusApiValues = (statusValues) => {
        const statusMapping = {
            'showAll': null,
            'Новая анкета': 'Новая анкета',
            'checked': 'Проверено',
            'Нужна доработка': 'Нужна доработка',
            'rejected': 'Отклонен'
        };

        return statusValues
            .filter(status => status !== 'showAll' && statusMapping[status])
            .map(status => statusMapping[status]);
    };

    const getVacancyApiValues = (vacancyValues, vacancyOptions) => {
        if (vacancyValues.includes('showAll')) {
            return [];
        }

        return vacancyValues
            .map(vacancyId => {
                const vacancy = vacancyOptions.find(option => option.value === vacancyId);
                return vacancy ? vacancy.title : null;
            })
            .filter(Boolean);
    };

    const handleCheckboxChange = (vacancyKey, isChecked) => {
        setSelectedKeys(prev => {
            if (isChecked) {
                return prev.includes(vacancyKey) ? prev : [...prev, vacancyKey];
            } else {
                return prev.filter(key => key !== vacancyKey);
            }
        });
    };

    const handleSelectAll = () => {
        const allVacancyKeys = candidates.map(candidate => candidate.vacancyKey);
        const allSelected = allVacancyKeys.every(key => selectedKeys.includes(key));

        if (allSelected) {
            setSelectedKeys(prev => prev.filter(key => !allVacancyKeys.includes(key)));
        } else {
            setSelectedKeys(prev => {
                const newKeys = allVacancyKeys.filter(key => !prev.includes(key));
                return [...prev, ...newKeys];
            });
        }
    };

    // Функция для скачивания отдельной анкеты
    const handleSingleDownload = async (vacancyKey, candidateName) => {
        setSingleDownloadLoading(prev => ({ ...prev, [vacancyKey]: true }));

        try {
            const token = getAccessToken();
            if (!token) {
                throw new Error('Токен авторизации не найден');
            }

            // Всегда скачиваем в PDF формате для отдельных анкет
            const url = `/api/v1/export/pdf-format?keys=${encodeURIComponent(vacancyKey)}`;

            const headers = {
                'accept': 'application/json',
                'Authorization': `Bearer ${token}`
            };

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
                    throw new Error('Файл не найден или некорректный ключ');
                } else {
                    throw new Error(`Ошибка сервера: ${response.status}`);
                }
            }

            const blob = await response.blob();

            const downloadUrl = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = downloadUrl;

            // Формируем имя файла с именем кандидата и текущей датой
            const fileName = `${candidateName.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
            link.download = fileName;

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            window.URL.revokeObjectURL(downloadUrl);

            console.log(`Успешно скачана анкета кандидата: ${candidateName}`);

        } catch (err) {
            console.error('Ошибка при скачивании анкеты:', err);
            console.error(`Ошибка при скачивании анкеты: ${err.message}`);
        } finally {
            setSingleDownloadLoading(prev => ({ ...prev, [vacancyKey]: false }));
        }
    };

    const handleDownload = async () => {
        setDownloadLoading(true);

        try {
            const token = getAccessToken();
            if (!token) {
                throw new Error('Токен авторизации не найден');
            }

            const endpoint = selectedFormat === '.pdf' ? 'pdf-format' : 'xlsx-format';
            let url = `/api/v1/export/${endpoint}`;

            if (selectedKeys.length > 0) {
                const keysParam = selectedKeys.join(',');
                url += `?keys=${encodeURIComponent(keysParam)}`;
            }

            const headers = {
                'accept': 'application/json',
                'Authorization': `Bearer ${token}`
            };

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

            const blob = await response.blob();

            const downloadUrl = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = downloadUrl;

            const fileName = selectedKeys.length > 0
                ? `candidates_export_${new Date().toISOString().split('T')[0]}${selectedFormat}`
                : `all_candidates_export_${new Date().toISOString().split('T')[0]}${selectedFormat}`;
            link.download = fileName;

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            window.URL.revokeObjectURL(downloadUrl);

            const exportMessage = selectedKeys.length > 0
                ? `Успешно скачано ${selectedKeys.length} анкет в формате ${selectedFormat}`
                : `Успешно скачаны все анкеты в формате ${selectedFormat}`;
            console.log(exportMessage);

        } catch (err) {
            console.error('Ошибка при скачивании:', err);
            console.error(`Ошибка при скачивании: ${err.message}`);
        } finally {
            setDownloadLoading(false);
        }
    };


    const fetchCandidates = async (page = 1, useFilters = false) => {
        setLoading(true);
        setError('');

        try {
            const token = getAccessToken();
            if (!token) {
                throw new Error('Токен авторизации не найден');
            }

            let url = `/api/v1/candidates/?page=${page}`;

            // Если есть активные фильтры и нужно их использовать
            if (useFilters && activeFilters) {
                const queryParams = [];

                // Добавляем параметры фильтров
                if (activeFilters.dateRange.start && activeFilters.dateRange.end) {
                    const dateRange = formatApiDateRange(
                        activeFilters.dateRange.start,
                        activeFilters.dateRange.end,
                        activeFilters.dateRange.type
                    );
                    if (dateRange) {
                        switch (activeFilters.dateRange.type) {
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

                const statusValues = getStatusApiValues(activeFilters.status);
                if (statusValues.length > 0) {
                    queryParams.push(`candidate_statuses=${statusValues.join(',')}`);
                }

                // Для вакансий понадобится доступ к vacancyOptions, но пока можем пропустить
                // const vacancyValues = getVacancyApiValues(activeFilters.vacancy, vacancyOptions);
                // if (vacancyValues.length > 0) {
                //     queryParams.push(`vacancy_title=${vacancyValues.join(',')}`);
                // }

                if (queryParams.length > 0) {
                    url += `&${queryParams.join('&')}`;
                }
            }

            const headers = {
                'accept': '*/*',
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            };

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
                } else {
                    throw new Error(`Ошибка сервера: ${response.status}`);
                }
            }

            const data = await response.json();


            if (data.response && data.attributes) {
                const transformedCandidates = data.attributes.data.map(candidate => ({
                    id: candidate.id,
                    name: `${candidate.last_name} ${candidate.first_name} ${candidate.middle_name || ''}`.trim(),
                    datetime: formatDateTime(candidate.created_at || new Date().toISOString()),
                    vacancy: candidate.vacancy?.attributes?.title || 'Не указана',
                    status: candidate.status || 'Не определен',
                    statusID: getStatusId(candidate.status),
                    hasVacancyComment: candidate.comment,
                    vacancyKey: candidate.key,
                    fullData: candidate
                }));
                console.log('данные - ' + transformedCandidates)
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

    const handleRowClick = (candidate, event) => {
        if (event.target.type === 'checkbox' ||
            event.target.closest('button') ||
            event.target.closest('label')) {
            return;
        }

        if (onRowClick) {
            onRowClick(candidate.vacancyKey);
        }
    };

    // Изменить обработчик смены страницы
    const handlePageChange = (page) => {
        if (page >= 1 && page <= pagination.last_page && page !== pagination.current_page) {
            // Если есть активные фильтры, используем их при пагинации
            fetchCandidates(page, activeFilters !== null);
        }
    };

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


    useEffect(() => {
        if (isAuthorized) {
            if (filteredData) {
                // Если есть отфильтрованные данные, используем их
                const transformedCandidates = filteredData.attributes.data.map(candidate => ({
                    id: candidate.id,
                    name: `${candidate.last_name} ${candidate.first_name} ${candidate.middle_name || ''}`.trim(),
                    datetime: formatDateTime(candidate.created_at || new Date().toISOString()),
                    vacancy: candidate.vacancy?.attributes?.title || 'Не указана',
                    status: candidate.status || 'Не определен',
                    statusID: getStatusId(candidate.status),
                    hasVacancyComment: candidate.comment,
                    vacancyKey: candidate.key,
                    fullData: candidate
                }));
                setCandidates(transformedCandidates);
                setPagination({
                    current_page: filteredData.attributes.current_page,
                    last_page: filteredData.attributes.last_page,
                    total: filteredData.attributes.total,
                    per_page: filteredData.attributes.per_page,
                    from: filteredData.attributes.from,
                    to: filteredData.attributes.to
                });
            } else {
                // Если нет отфильтрованных данных, загружаем обычные
                fetchCandidates();
            }
        }
    }, [filteredData, isAuthorized]);

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

    // Если пользователь не авторизован, не рендерим компонент (произойдет редирект)
    if (!isAuthorized) {
        return null;
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
                    <img src="/img/filters.png" alt="PNG картинка, фильтров" />
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
                            <th></th>
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
                                            className = {"redactBtn"}
                                            onClick={(e) => e.stopPropagation()}
                                            title = {candidate.hasVacancyComment }
                                        >
                                            <img src="/img/pen.png" alt="Редактировать анкету" />
                                        </button>
                                    )}
                                    <button
                                        id={`downloadBtn${candidate.id}`}
                                        className = {"downloadBtn"}
                                        onClick={(e) => {
                                            e.stopPropagation();
                                            handleSingleDownload(candidate.vacancyKey, candidate.name);
                                        }}
                                        disabled={singleDownloadLoading[candidate.vacancyKey]}
                                        title={singleDownloadLoading[candidate.vacancyKey] ? 'Скачивание...' : 'Скачать анкету в PDF'}
                                        >
                                        {singleDownloadLoading[candidate.vacancyKey] ? (
                                            <span>⏳</span>
                                        ) : (
                                            <svg 
                                            width="20" 
                                            height="20" 
                                            viewBox="0 0 24 24" 
                                            fill="none" 
                                            xmlns="http://www.w3.org/2000/svg"
                                            >
                                            <path 
                                                d="M12 3V16M12 16L18 10M12 16L6 10M4 21H20" 
                                                stroke="currentColor" 
                                                strokeWidth="3" 
                                                strokeLinecap="round" 
                                                strokeLinejoin="round"
                                            />
                                            </svg>
                                        )}
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
   function FiltersCalendar({ isOpen, onClose, filtersButtonRef, onFiltersApply }) {
        const [selectedFilters, setSelectedFilters] = useState({
            status: ['showAll'],
            vacancy: ['showAll'],
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

        const getStatusApiValues = (statusValues) => {
            if (statusValues.includes('showAll') || statusValues.length === 0) {
                return [];
            }
            
            const statusMapping = {
                'Новая анкета': 'Новая анкета',
                'checked': 'Проверено',
                'Нужна доработка': 'Нужна доработка',
                'rejected': 'Отклонен'
            };

            return statusValues
                .filter(status => statusMapping[status])
                .map(status => statusMapping[status]);
        };

        const getVacancyApiValues = (vacancyValues) => {
            if (vacancyValues.includes('showAll') || vacancyValues.length === 0) {
                return [];
            }

            return vacancyValues
                .map(vacancyId => {
                    const vacancy = vacancyOptions.find(option => option.value === vacancyId);
                    return vacancy ? vacancy.title : null;
                })
                .filter(Boolean);
        };

       const calendarPanelRef = useRef(null);

       const statusFilters = [
           {value: 'showAll', text: 'Показать все'},
           {value: 'Новая анкета', text: 'Новая анкета'},
           {value: 'Проверен', text: 'Проверен'},
           {value: 'Нужна доработка', text: 'Нужна доработка'},
           {value: 'Отклонен', text: 'Отклонен'}
       ];

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
                   const vacancies = [
                       {value: 'showAll', text: 'Показать все', title: null},
                       ...data.attributes.map(vacancy => ({
                           value: vacancy.id.toString(),
                           text: vacancy.title,
                           title: vacancy.title
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
                    if (value === 'showAll') {
                        // Если нажата "Показать все", очищаем все остальные фильтры этого типа
                        newFilters[filter] = ['showAll'];
                    } else {
                        // Если нажат любой другой фильтр
                        if (newFilters[filter].includes('showAll')) {
                            // Убираем "Показать все" и добавляем выбранный фильтр
                            newFilters[filter] = [value];
                        } else {
                            // Обычная логика добавления/удаления фильтра
                            if (newFilters[filter].includes(value)) {
                                newFilters[filter] = newFilters[filter].filter(v => v !== value);
                                // Если не осталось выбранных фильтров, возвращаем "Показать все"
                                if (newFilters[filter].length === 0) {
                                    newFilters[filter] = ['showAll'];
                                }
                            } else {
                                newFilters[filter] = [...newFilters[filter], value];
                            }
                        }
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

       // Изменить функцию handleApplyFilters
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

               const queryParams = [];

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

               const statusValues = getStatusApiValues(updatedFilters.status);
               if (statusValues.length > 0) {
                   queryParams.push(`candidate_statuses=${statusValues.join(',')}`);
               }

               const vacancyValues = getVacancyApiValues(updatedFilters.vacancy);
               if (vacancyValues.length > 0) {
                   queryParams.push(`vacancy_title=${vacancyValues.join(',')}`);
               }

               const queryString = queryParams.join('&');

               console.log('=== ФИЛЬТРЫ КАЛЕНДАРЯ ===');
               console.log('Применяемые фильтры:', {
                   dateRange: updatedFilters.dateRange,
                   status: updatedFilters.status,
                   vacancy: updatedFilters.vacancy
               });
               console.log('Параметры API запроса:', queryString);
               console.log('Полный URL запроса:', `/api/v1/candidates${queryString ? '?' + queryString : ''}`);

               const accessToken = getAccessTokenFromCookie();

               if (!accessToken) {
                   throw new Error('Токен доступа не найден');
               }

               const apiUrl = `/api/v1/candidates${queryString ? '?' + queryString : ''}`;
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

               setCandidatesData(data);

               // Передаем данные в родительский компонент
               if (onFiltersApply) {
                   onFiltersApply(data, updatedFilters);
               }

               console.log('Фильтры успешно применены, данные загружены');

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

       // Остальные функции календаря остаются без изменений...
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
                    <div className="center-card" style={{minWidth: '800px', height: '105%', paddingBottom: '50px'}}>
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

                        <div className="formRow justify-center" style={{marginTop: '25px', gap: '1rem'}}>
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
                                    setSelectedFilters({
                                        status: ['showAll'], 
                                        vacancy: ['showAll'], 
                                        dateRange: {type: 'dates', start: null, end: null}
                                    });
                                    setStartDate(null);
                                    setEndDate(null);
                                    setCurrentRangeType('dates');
                                    setCandidatesError('');
                                    setCandidatesData(null);
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

    function App() {
    const [isCalendarOpen, setIsCalendarOpen] = useState(false);
    const [selectedVacancyKey, setSelectedVacancyKey] = useState(null);

    // Добавляем недостающие состояния для фильтров
    const [filteredData, setFilteredData] = useState(null);
    const [activeFilters, setActiveFilters] = useState(null);

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

    // Обработчик применения фильтров
    const handleFiltersApply = (data, filters) => {
        setFilteredData(data);
        setActiveFilters(filters);
        setIsCalendarOpen(false); // Закрываем панель фильтров после применения
    };

    // Обработчик сброса фильтров
    const handleFiltersReset = () => {
        setFilteredData(null);
        setActiveFilters(null);
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
                        setSelectedVacancyKey={setSelectedVacancyKey}
                    />
                ) : (
                    // Показываем таблицу кандидатов, если кандидат не выбран
                    <>
                        <CandidatesTable
                            onFiltersClick={handleFiltersClick}
                            onRowClick={handleRowClick}
                            filtersButtonRef={filtersButtonRef}
                            filteredData={filteredData}
                            activeFilters={activeFilters}
                            onFiltersReset={handleFiltersReset}
                        />
                        <FiltersCalendar
                            isOpen={isCalendarOpen}
                            onClose={handleCalendarClose}
                            filtersButtonRef={filtersButtonRef}
                            onFiltersApply={handleFiltersApply}
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
