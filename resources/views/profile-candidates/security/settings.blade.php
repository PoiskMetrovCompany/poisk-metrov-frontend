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
    
    /* Стили для модального окна */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-overlay.show {
        opacity: 1;
    }
    
    .modal-content {
        transform: scale(0.8) translateY(20px);
        transition: transform 0.3s ease;
    }
    
    .modal-overlay.show .modal-content {
        transform: scale(1) translateY(0);
    }
</style>

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
                        <a  href="/profile-candidates/security/">Кандидаты</a>
                        <a href="/profile-candidates/security/settings" className="active">Настройки</a>
                    </div>
                    <div style={{display: 'flex', justifyContent: 'space-between', minWidth: '250px'}}>
                        <button id="notifBtn"><img src="/img/ring.png" alt="Уведомлений нет" /></button>
                        <button id="exitBtn">Выйти из ЛК <img src="/img/arowRight.png" alt="Стрелочка вправо" /></button>
                    </div>
                </div>
            </header>
        );
    }

    // Компонент модального окна подтверждения удаления
    function DeleteConfirmationModal({ isOpen, onClose, onConfirm, roleName }) {
        const [isVisible, setIsVisible] = useState(false);

        useEffect(() => {
            if (isOpen) {
                // Небольшая задержка для плавного появления
                setTimeout(() => setIsVisible(true), 10);
            } else {
                setIsVisible(false);
            }
        }, [isOpen]);

        const handleOverlayClick = (e) => {
            if (e.target === e.currentTarget) {
                onClose();
            }
        };

        const handleConfirm = () => {
            onConfirm();
            onClose();
        };

        if (!isOpen) return null;

        return (
            <div 
                className={`modal-overlay ${isVisible ? 'show' : ''}`}
                onClick={handleOverlayClick}
            >
                <main className="modal-content">
                    <section>
                        <div className="center-card" style={{maxHeight: '263px'}}>
                            <h1>Удалить вакансию</h1>
                            <p>Вы точно уверены, что хотите удалить эту вакансию из анкет кандидатов?</p>
                            <div className="formRow justify-space-between">
                                <button 
                                    style={{maxWidth: '150px'}} 
                                    className="formBtn btn-active" 
                                    onClick={onClose}
                                >
                                    Нет, оставить
                                </button>
                                <button 
                                    style={{maxWidth: '150px'}} 
                                    className="formBtn btn-inactive" 
                                    onClick={handleConfirm}
                                >
                                    Да, удалить
                                </button>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
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

        // Состояния для модального окна удаления
        const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
        const [roleToDelete, setRoleToDelete] = useState(null);

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

        // Функция для отмены добавления новой роли
        const handleCancelAdd = () => {
            setNewRole('');
            setIsAdding(false);
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
                // ВАЖНО: Завершаем режим редактирования после сохранения
                setIsEditing(false);

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

        // Обработчик нажатия на кнопку удаления - показывает модальное окно
        const handleDeleteClick = (index) => {
            setRoleToDelete(index);
            setIsDeleteModalOpen(true);
        };

        // Закрытие модального окна
        const handleDeleteModalClose = () => {
            setIsDeleteModalOpen(false);
            setRoleToDelete(null);
        };

        // Подтверждение удаления - вызывается после нажатия "Да, удалить"
        const confirmDelete = async () => {
            if (roleToDelete === null) return;

            const index = roleToDelete;
            const roleToDeleteData = roles[index];

            console.log('=== НАЧАЛО УДАЛЕНИЯ ВАКАНСИИ ===');
            console.log('Индекс удаляемой роли:', index);
            console.log('Данные роли для удаления:', roleToDeleteData);
            console.log('Key для удаления:', roleToDeleteData.key);

            // Сразу удаляем из UI
            const updatedRoles = roles.filter((_, i) => i !== index);
            setRoles(updatedRoles);

            // Если удаляется редактируемая роль, сбрасываем редактирование
            if (editingIndex === index) {
                setEditingIndex(null);
                setEditingRole('');
                console.log('Сброшено редактирование удаленной роли');
            }

            // ВАЖНО: Завершаем режим редактирования после удаления
            setIsEditing(false);

            // Отправляем DELETE запрос на сервер
            try {
                const accessToken = getAccessTokenFromCookie();
                console.log('Токен доступа для удаления:', accessToken ? 'найден' : 'НЕ найден');

                if (accessToken) {
                    const deleteUrl = `/api/v1/vacancy/destroy?key=${roleToDeleteData.key}`;
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
                                                    onClick={() => handleDeleteClick(index)}
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
                                    <div className="input-container" style={{minWidth: '200px'}}>
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
                                    <div className="input-container" style={{minWidth: '200px'}}>
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
                                
                                {/* Логика для второй кнопки */}
                                {isAdding ? (
                                    // Когда добавляем роль - показываем кнопку "Отменить"
                                    <button
                                        className="formBtn small btn-inactive"
                                        onClick={handleCancelAdd}
                                    >
                                        Отменить
                                    </button>
                                ) : (
                                    // В остальных случаях - кнопка редактирования
                                    <button
                                        className={`formBtn small ${isEditing ? 'btn-active' : 'btn-inactive'}`}
                                        disabled={editingIndex !== null || isLoadingRoles}
                                        onClick={handleEditMode}
                                    >
                                        {isEditing ? 'Завершить редактирование' : 'Редактировать'}
                                    </button>
                                )}
                            </div>
                        </div>
                    </section>
                </main>

                {/* Модальное окно подтверждения удаления */}
                <DeleteConfirmationModal
                    isOpen={isDeleteModalOpen}
                    onClose={handleDeleteModalClose}
                    onConfirm={confirmDelete}
                    roleName={roleToDelete !== null ? roles[roleToDelete]?.title : ''}
                />
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
               <Header />
               <CandidatesSettings />
           </>
        );
    }

    // Монтируем главное приложение
    ReactDOM.render(React.createElement(App), document.getElementById('root'));
    <?php echo '@endverbatim'; ?>
</script>
</body>
</html>