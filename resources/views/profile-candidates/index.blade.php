<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система регистрации</title>
    @vite(['resources/css/candidatesProfiles/index.css'])
    <style>
        .successMarker{
            width: 56px;
            height: 56px;
            background: rgb(237, 255, 233);
            border-radius: 60px;
        }

        .formRow{
            margin-top: 1rem;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .success-modal {
            background: white;
            border-radius: 12px;
            padding: 40px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .success-modal h1 {
            margin: 20px 0 10px 0;
            color: #181817;
        }

        .success-modal p {
            margin: 0 0 30px 0;
            color: #666;
        }
    </style>
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
    
    const checkAuthAndRedirect = () => {
    const accessToken = getAccessTokenFromCookie();
    
    if (!accessToken) {
        window.location.href = '/profile-candidates/login';
        return false;
    }
        
        return true;
    };

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
                    <tbody>
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
                    </tbody>
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
                    <tbody>
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
                    </tbody>
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
                    <tbody>
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
                    </tbody>
                </table>
            </div>
        );
    };

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
                return status ? status.key : '';
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
                    <img src="/img/Logo с текстом.png" alt="Картинка с логотипом агенства и подписью Поиск метров" />
                </header>

                <article>
                    <h1>Анкета кандидата</h1>
                    <p>Заполните анкету, чтобы подать заявку на вакансию</p>
                </article>

                <main>
                    <section>
                        <div className="center-card big">


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
                                <div class="center-card" style = "max-height: 364px">
                                <div style="margin-top: 0;" class = "formRow justify-center">
                                <div class = "successMarker">
                                <svg width="56" height="56" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="20" cy="20" r="18" fill="#e8f5e8" stroke="#4caf50" stroke-width="2"/>
                                <polyline points="12,20 17,25 28,14" stroke="#4caf50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                </svg>
                                </div>
                                </div>
                                <div class = "formRow justify-center">
                                <h1>Анкета успешно отправлена</h1>
                                </div>
                                <div class = "formRow justify-center">
                                <p>Мы успешно получили вашу анкету</p>
                                </div>
                                <div class = "formRow justify-center">
                                <button id="closeNotification" class="formBtn btn-active">
                                Закрыть
                                </button>
                                </div>
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
        const filtersButtonRef = useRef(null);

        const handleFiltersClick = () => {
            setIsCalendarOpen(true);
        };

        const handleCalendarClose = () => {
            setIsCalendarOpen(false);
        };

        return (
            <>
                <CandidateForm />
            </>
        );
    }



    // Render the app
    ReactDOM.render(<App />, document.getElementById('root'));
    <?php echo '@endverbatim'; ?>
</script>
</body>
</html>
