<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма кандидата</title>
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

        .formRow{
            margin-top: 2rem;
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
    // Функции форматирования (добавить в начало компонента)
    const formatDate = (value) => {
        const numbers = value.replace(/\D/g, '');
        if (numbers.length <= 2) {
            return numbers;
        } else if (numbers.length <= 4) {
            return numbers.slice(0, 2) + '.' + numbers.slice(2);
        } else {
            return numbers.slice(0, 2) + '.' + numbers.slice(2, 4) + '.' + numbers.slice(4, 8);
        }
    };

    const formatMobilePhone = (value) => {
        const numbers = value.replace(/\D/g, '');
        let formattedNumbers = numbers;
        if (numbers.length > 0 && numbers[0] !== '7') {
            formattedNumbers = '7' + numbers;
        }
        
        if (formattedNumbers.length <= 1) {
            return '+7';
        } else if (formattedNumbers.length <= 4) {
            return '+7 (' + formattedNumbers.slice(1);
        } else if (formattedNumbers.length <= 7) {
            return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4);
        } else if (formattedNumbers.length <= 9) {
            return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4, 7) + '-' + formattedNumbers.slice(7);
        } else {
            return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4, 7) + '-' + formattedNumbers.slice(7, 9) + '-' + formattedNumbers.slice(9, 11);
        }
    };

    const handleInputChange = (name, value) => {
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleDateChange = (name, value) => {
        const formattedValue = formatDate(value);
        handleInputChange(name, formattedValue);
    };

    const handlePhoneChange = (name, value) => {
        const formattedValue = formatMobilePhone(value);
        handleInputChange(name, formattedValue);
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
                            placeholder="01.01.1990"
                            maxLength="10"
                            value={formData.dateOfBirthTable || ''}
                            onChange={(e) => handleDateChange('dateOfBirthTable', e.target.value)}
                        />
                    </td>
                    <td>
                        <input
                            type="text"
                            name="phoneNumberTable"
                            placeholder="+7 (905) 123-45-67"
                            maxLength="18"
                            value={formData.phoneNumberTable || ''}
                            onChange={(e) => handlePhoneChange('phoneNumberTable', e.target.value)}
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

// 2. RelativeTable - аналогичные изменения
const RelativeTable = ({ index, formData, setFormData }) => {
    // Функции форматирования (добавить в начало компонента)
    const formatDate = (value) => {
        const numbers = value.replace(/\D/g, '');
        if (numbers.length <= 2) {
            return numbers;
        } else if (numbers.length <= 4) {
            return numbers.slice(0, 2) + '.' + numbers.slice(2);
        } else {
            return numbers.slice(0, 2) + '.' + numbers.slice(2, 4) + '.' + numbers.slice(4, 8);
        }
    };

    const formatMobilePhone = (value) => {
        const numbers = value.replace(/\D/g, '');
        let formattedNumbers = numbers;
        if (numbers.length > 0 && numbers[0] !== '7') {
            formattedNumbers = '7' + numbers;
        }
        
        if (formattedNumbers.length <= 1) {
            return '+7';
        } else if (formattedNumbers.length <= 4) {
            return '+7 (' + formattedNumbers.slice(1);
        } else if (formattedNumbers.length <= 7) {
            return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4);
        } else if (formattedNumbers.length <= 9) {
            return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4, 7) + '-' + formattedNumbers.slice(7);
        } else {
            return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4, 7) + '-' + formattedNumbers.slice(7, 9) + '-' + formattedNumbers.slice(9, 11);
        }
    };

    const formatNameInput = (value) => {
        return value.replace(/[^а-яёА-ЯЁa-zA-Z\s\-]/g, '');
    };

    const handleInputChange = (name, value) => {
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleDateChange = (name, value) => {
        const formattedValue = formatDate(value);
        handleInputChange(name, formattedValue);
    };

    const handlePhoneChange = (name, value) => {
        const formattedValue = formatMobilePhone(value);
        handleInputChange(name, formattedValue);
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
                            onChange={(e) => {
                                const formattedValue = formatNameInput(e.target.value);
                                handleInputChange(`FIORelative${index}`, formattedValue);
                            }}
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input
                            type="text"
                            name={`dateOfBirthRelative${index}`}
                            placeholder="01.01.1990"
                            maxLength="10"
                            value={formData[`dateOfBirthRelative${index}`] || ''}
                            onChange={(e) => handleDateChange(`dateOfBirthRelative${index}`, e.target.value)}
                        />
                    </td>
                    <td>
                        <input
                            type="text"
                            name={`phoneNumberRelative${index}`}
                            placeholder="+7 (905) 123-45-67"
                            maxLength="18"
                            value={formData[`phoneNumberRelative${index}`] || ''}
                            onChange={(e) => handlePhoneChange(`phoneNumberRelative${index}`, e.target.value)}
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

// 3. ChildrenTable - аналогичные изменения
const ChildrenTable = ({ index, formData, setFormData }) => {
    // Функции форматирования (добавить в начало компонента)
    const formatDate = (value) => {
        const numbers = value.replace(/\D/g, '');
        if (numbers.length <= 2) {
            return numbers;
        } else if (numbers.length <= 4) {
            return numbers.slice(0, 2) + '.' + numbers.slice(2);
        } else {
            return numbers.slice(0, 2) + '.' + numbers.slice(2, 4) + '.' + numbers.slice(4, 8);
        }
    };

    const formatMobilePhone = (value) => {
        const numbers = value.replace(/\D/g, '');
        let formattedNumbers = numbers;
        if (numbers.length > 0 && numbers[0] !== '7') {
            formattedNumbers = '7' + numbers;
        }
        
        if (formattedNumbers.length <= 1) {
            return '+7';
        } else if (formattedNumbers.length <= 4) {
            return '+7 (' + formattedNumbers.slice(1);
        } else if (formattedNumbers.length <= 7) {
            return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4);
        } else if (formattedNumbers.length <= 9) {
            return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4, 7) + '-' + formattedNumbers.slice(7);
        } else {
            return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4, 7) + '-' + formattedNumbers.slice(7, 9) + '-' + formattedNumbers.slice(9, 11);
        }
    };

    const formatNameInput = (value) => {
        return value.replace(/[^а-яёА-ЯЁa-zA-Z\s\-]/g, '');
    };
   
    const handleInputChange = (name, value) => {
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleDateChange = (name, value) => {
        const formattedValue = formatDate(value);
        handleInputChange(name, formattedValue);
    };

    const handlePhoneChange = (name, value) => {
        const formattedValue = formatMobilePhone(value);
        handleInputChange(name, formattedValue);
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
                            onChange={(e) => {
                                const formattedValue = formatNameInput(e.target.value);
                                handleInputChange(`FIOChildren${index}`, formattedValue);
                            }}
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input
                            type="text"
                            name={`dateOfBirthChildren${index}`}
                            placeholder="01.01.1990"
                            maxLength="10"
                            value={formData[`dateOfBirthChildren${index}`] || ''}
                            onChange={(e) => handleDateChange(`dateOfBirthChildren${index}`, e.target.value)}
                        />
                    </td>
                    <td>
                        <input
                            type="text"
                            name={`phoneNumberChildren${index}`}
                            placeholder="+7 (905) 123-45-67"
                            maxLength="18"
                            value={formData[`phoneNumberChildren${index}`] || ''}
                            onChange={(e) => handlePhoneChange(`phoneNumberChildren${index}`, e.target.value)}
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

        useEffect(() => {
            const checkAuthToken = () => {
                const accessToken = getAccessTokenFromCookie();
                
                if (!accessToken) {
                    // Получаем текущий URL и добавляем /login
                    const currentUrl = window.location.origin + window.location.pathname;
                    const loginUrl = currentUrl.endsWith('/') ? currentUrl + 'login' : currentUrl + '/login';
                    
                    // Выполняем редирект
                    window.location.href = loginUrl;
                }
            };

            // Проверяем токен при монтировании компонента
            checkAuthToken();
        }, []);

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

        //Состояния для селекта города
        const [selectedCity, setSelectedCity] = useState('');
        const [showCityOptions, setShowCityOptions] = useState(false);


        // Централизованное состояние для данных формы
        const [formData, setFormData] = useState({});

        // Хранение ключей для API запросов
        const [vacancyKey, setVacancyKey] = useState('');
        const [maritalStatusKey, setMaritalStatusKey] = useState('');

        // Функция для обновления данных формы
        const handleFormDataChange = (name, value) => {
            setFormData(prev => ({ ...prev, [name]: value }));
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

        const formatNameInput = (value) => {
            return value.replace(/[^а-яёА-ЯЁa-zA-Z\s\-]/g, '');
        };
        const formatDate = (value) => {
            // Удаляем все символы кроме цифр
            const numbers = value.replace(/\D/g, '');
            
            // Применяем маску DD.MM.YYYY
            if (numbers.length <= 2) {
                return numbers;
            } else if (numbers.length <= 4) {
                return numbers.slice(0, 2) + '.' + numbers.slice(2);
            } else {
                return numbers.slice(0, 2) + '.' + numbers.slice(2, 4) + '.' + numbers.slice(4, 8);
            }
        };

        const formatMobilePhone = (value) => {
            // Удаляем все символы кроме цифр
            const numbers = value.replace(/\D/g, '');
            
            // Если первая цифра не 7, добавляем 7
            let formattedNumbers = numbers;
            if (numbers.length > 0 && numbers[0] !== '7') {
                formattedNumbers = '7' + numbers;
            }
            
            // Применяем маску +7 (XXX) XXX-XX-XX
            if (formattedNumbers.length <= 1) {
                return '+7';
            } else if (formattedNumbers.length <= 4) {
                return '+7 (' + formattedNumbers.slice(1);
            } else if (formattedNumbers.length <= 7) {
                return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4);
            } else if (formattedNumbers.length <= 9) {
                return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4, 7) + '-' + formattedNumbers.slice(7);
            } else {
                return '+7 (' + formattedNumbers.slice(1, 4) + ') ' + formattedNumbers.slice(4, 7) + '-' + formattedNumbers.slice(7, 9) + '-' + formattedNumbers.slice(9, 11);
            }
        };

        const formatHomePhone = (value) => {
            // Удаляем все символы кроме цифр
            const numbers = value.replace(/\D/g, '');
            
            // Применяем маску XXX XXX
            if (numbers.length <= 3) {
                return numbers;
            } else {
                return numbers.slice(0, 3) + ' ' + numbers.slice(3, 6);
            }
        };

        const formatPassport = (value) => {
            // Удаляем все символы кроме цифр
            const numbers = value.replace(/\D/g, '');
            
            // Применяем маску XXXX XXXXXX
            if (numbers.length <= 4) {
                return numbers;
            } else {
                return numbers.slice(0, 4) + ' ' + numbers.slice(4, 10);
            }
        };

        // Обработчики изменения с масками
        const handleDateChange = (name, value) => {
            const formattedValue = formatDate(value);
            handleFormDataChange(name, formattedValue);
        };

        const handleMobilePhoneChange = (name, value) => {
            const formattedValue = formatMobilePhone(value);
            handleFormDataChange(name, formattedValue);
        };

        const handleHomePhoneChange = (name, value) => {
            const formattedValue = formatHomePhone(value);
            handleFormDataChange(name, formattedValue);
        };

        const handlePassportChange = (name, value) => {
            const formattedValue = formatPassport(value);
            handleFormDataChange(name, formattedValue);
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

        const cityOptions = ['Новосибирск', 'Санкт-Петербург'];

        // Функция для закрытия всех select'ов при клике вне их
        useEffect(() => {
            const handleClickOutside = () => {
                setShowVacancyOptions(false);
                setShowMaritalOptions(false);
                setShowCityOptions(false);
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
                    city_work: selectedCity, 
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
                    family_partner: (selectedMaritalStatus === 'Состою в зарегистрированном браке') ? JSON.stringify({
                        full_name: rawFormData.FIOSuprug || '',
                        birth_date: formatDateForDatabase(rawFormData.dateOfBirthTable) || '',
                        phone: rawFormData.phoneNumberTable || '',
                        work_study_place: rawFormData.placeOfStudy || '',
                        residence_address: rawFormData.placeOfLiving || ''
                    }) : JSON.stringify({}),
                    adult_family_members: familyMembersData ? JSON.stringify(familyMembersData) : JSON.stringify([]),
                    adult_children: childrenData ? JSON.stringify(childrenData) : JSON.stringify([]),
                    serviceman: militaryDuty,
                    law_breaker: criminalResponsibility ? (rawFormData.whyPrisoner || 'Да') : 'Нет',
                    legal_entity: legalEntity ? (rawFormData.LegalEntity || 'Да') : 'Нет',
                    is_data_processing: personalDataChecked,
                    comment: 'Коммент'
                };

                console.table(apiData)

                // const logSubmittedData = (apiData, rawFormData, selectedVacancy, selectedCity, selectedMaritalStatus) => {
                //     console.group('🚀 ОТПРАВКА АНКЕТЫ - ДЕТАЛЬНЫЕ ДАННЫЕ');
                    
                //     // 1. Основная информация
                //     console.group('📋 1. ОСНОВНАЯ ИНФОРМАЦИЯ');
                //     console.log('Выбранная вакансия:', selectedVacancy);
                //     console.log('Выбранный город:', selectedCity);
                //     console.log('Семейное положение:', selectedMaritalStatus);
                //     console.log('ФИО кандидата:', rawFormData.FIO || 'Не указано');
                //     console.log('Дата рождения:', rawFormData.birthDate || 'Не указано');
                //     console.log('Место рождения:', rawFormData.birthPlace || 'Не указано');
                //     console.log('Мобильный телефон:', rawFormData.mobileNumber || 'Не указано');
                //     console.log('Email:', rawFormData.email || 'Не указано');
                //     console.groupEnd();

                //     // 2. Паспортные данные
                //     console.group('📄 2. ПАСПОРТНЫЕ ДАННЫЕ');
                //     console.log('Серия и номер:', rawFormData.passwordSeriaNumber || 'Не указано');
                //     console.log('Дата выдачи:', rawFormData.dateOfIssue || 'Не указано');
                //     console.log('Кем выдан:', rawFormData.issuedBy || 'Не указано');
                //     console.groupEnd();

                //     // 3. Адреса
                //     console.group('🏠 3. АДРЕСНАЯ ИНФОРМАЦИЯ');
                //     console.log('Постоянная регистрация:', rawFormData.adressOfPermanentReg || 'Не указано');
                //     console.log('Временная регистрация:', rawFormData.adressOfTemporaryReg || 'Не указано');
                //     console.log('Фактическое проживание:', rawFormData.adressOfFactialLiving || 'Не указано');
                //     console.groupEnd();

                //     // 4. Семейные данные
                //     console.group('👨‍👩‍👧‍👦 4. СЕМЕЙНАЯ ИНФОРМАЦИЯ');
                //     if (selectedMaritalStatus === 'Женат/Замужем' || selectedMaritalStatus === 'Состою в зарегистрированном браке') {
                //         console.log('Данные супруга:', rawFormData.FIOSuprug || 'Не указано');
                //         console.log('Дата рождения супруга:', rawFormData.dateOfBirthTable || 'Не указано');
                //         console.log('Телефон супруга:', rawFormData.phoneNumberTable || 'Не указано');
                //     }
                    
                //     // Дети
                //     const childrenData = JSON.parse(apiData.adult_children || '[]');
                //     if (childrenData.length > 0) {
                //         console.log('Количество детей старше 18 лет:', childrenData.length);
                //         childrenData.forEach((child, index) => {
                //             console.log(`Ребенок ${index + 1}:`, child.full_name);
                //         });
                //     } else {
                //         console.log('Детей старше 18 лет: нет');
                //     }
                    
                //     // Члены семьи
                //     const familyData = JSON.parse(apiData.adult_family_members || '[]');
                //     if (familyData.length > 0) {
                //         console.log('Количество членов семьи старше 18 лет:', familyData.length);
                //         familyData.forEach((member, index) => {
                //             console.log(`Член семьи ${index + 1}:`, member.relationship_and_name);
                //         });
                //     } else {
                //         console.log('Членов семьи старше 18 лет: нет');
                //     }
                //     console.groupEnd();

                //     // 5. Юридический статус
                //     console.group('⚖️ 5. ЮРИДИЧЕСКИЙ СТАТУС');
                //     console.log('Военнообязанный:', apiData.serviceman ? 'Да' : 'Нет');
                //     console.log('Привлечение к уголовной ответственности:', apiData.law_breaker);
                //     console.log('Учредитель юрлица:', apiData.legal_entity);
                //     console.log('Согласие на обработку данных:', apiData.is_data_processing ? 'Да' : 'Нет');
                //     console.groupEnd();

                //     // 6. Технические данные для API
                //     console.group('🔧 6. ТЕХНИЧЕСКИЕ ДАННЫЕ ДЛЯ API');
                //     console.log('Ключ вакансии:', apiData.vacancies_key);
                //     console.log('Ключ семейного положения:', apiData.marital_statuses_key);
                //     console.log('Статус кандидата:', apiData.status);
                //     console.groupEnd();

                //     // 7. Полный объект для отправки
                //     console.group('📦 7. ПОЛНЫЙ ОБЪЕКТ ДЛЯ ОТПРАВКИ В API');
                //     console.log('Размер объекта:', Object.keys(apiData).length, 'полей');
                //     console.table(apiData);
                //     console.groupEnd();

                //     // 8. Валидация данных
                //     console.group('✅ 8. ПРОВЕРКА ОБЯЗАТЕЛЬНЫХ ПОЛЕЙ');
                //     const requiredFields = {
                //         'ФИО': rawFormData.FIO,
                //         'Вакансия': selectedVacancy,
                //         'Дата рождения': rawFormData.birthDate,
                //         'Мобильный телефон': rawFormData.mobileNumber,
                //         'Email': rawFormData.email,
                //         'Серия и номер паспорта': rawFormData.passwordSeriaNumber,
                //         'Согласие на обработку данных': apiData.is_data_processing
                //     };

                //     let missingFields = [];
                //     Object.entries(requiredFields).forEach(([field, value]) => {
                //         if (!value || (typeof value === 'string' && value.trim() === '')) {
                //             missingFields.push(field);
                //             console.warn(`❌ ${field}: НЕ ЗАПОЛНЕНО`);
                //         } else {
                //             console.log(`✅ ${field}: заполнено`);
                //         }
                //     });

                //     if (missingFields.length > 0) {
                //         console.warn('⚠️ ВНИМАНИЕ: Не заполнены обязательные поля:', missingFields);
                //     } else {
                //         console.log('🎉 Все обязательные поля заполнены');
                //     }
                //     console.groupEnd();

                //     console.groupEnd();
                // };

                // logSubmittedData(apiData, rawFormData, selectedVacancy, selectedCity, selectedMaritalStatus);

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

            {!submitSuccess && (
                <article>
                    <h1>Анкета кандидата</h1>
                    <p>Заполните анкету, чтобы подать заявку на вакансию</p>
                </article>
            )}

            <main>
                <section>
                    {submitSuccess ? (
                        // Показываем только сообщение об успехе
                        <div className="center-card" style={{maxHeight: '364px'}}>
                            <div style={{marginTop: 0}} className="formRow justify-center">
                                <div className="successMarker">
                                    <svg width="56" height="56" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="20" cy="20" r="18" fill="#e8f5e8" stroke="#4caf50" strokeWidth="2"/>
                                        <polyline points="12,20 17,25 28,14" stroke="#4caf50" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" fill="none"/>
                                    </svg>
                                </div>
                            </div>
                            <div className="formRow justify-center">
                                <h1>Анкета успешно отправлена</h1>
                            </div>
                            <div className="formRow justify-center">
                                <p>Мы успешно получили вашу анкету</p>
                            </div>
                            <div className="formRow justify-center">
                                <button
                                    id="closeNotification"
                                    className="formBtn btn-active"
                                    onClick={() => window.location.reload()}
                                >
                                    Закрыть
                                </button>
                            </div>
                        </div>
                    ) : (
                        // Показываем основную форму
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
                                            setShowCityOptions(false); 
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
                                    <label htmlFor="City" className="formLabel">Город работы</label>
                                    <CustomSelect
                                        options={cityOptions}
                                        placeholder="Выберите город в котором хотите работать"
                                        value={selectedCity}
                                        show={showCityOptions}
                                        isLoading={false}
                                        error=""
                                        onToggle={() => {
                                            setShowCityOptions(!showCityOptions);
                                            setShowVacancyOptions(false);
                                            setShowMaritalOptions(false);
                                        }}
                                        onSelect={(option) => {
                                            setSelectedCity(option);
                                            setShowCityOptions(false);
                                        }}
                                    />
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
                                        onChange={(e) => handleFormDataChange('FIO', formatNameInput(e.target.value))}
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
                                            maxLength="10"
                                            value={formData.birthDate || ''}
                                            onChange={(e) => handleDateChange('birthDate', e.target.value)}
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
                                            type="text"
                                            name="mobileNumber"
                                            className="formInput"
                                            placeholder="+7 (905) 123-45-67"
                                            maxLength="18"
                                            value={formData.mobileNumber || ''}
                                            onChange={(e) => handleMobilePhoneChange('mobileNumber', e.target.value)}
                                        />
                                </div>

                                <div className="input-container w-49">
                                    <label htmlFor="domesticNumber" className="formLabel">Домашний телефон</label>
                                        <input
                                            style={{width: '100%'}}
                                            type="text"
                                            name="domesticNumber"
                                            className="formInput"
                                            placeholder="999 999"
                                            maxLength="7"
                                            value={formData.domesticNumber || ''}
                                            onChange={(e) => handleHomePhoneChange('domesticNumber', e.target.value)}
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
                                        type="tel"
                                        name="INN"
                                        className="formInput"
                                        placeholder="123456789012"
                                        maxLength="12"
                                        value={formData.INN || ''}
                                        onChange={(e) => {
                                            // Разрешаем только цифры и ограничиваем до 12 символов
                                            const value = e.target.value.replace(/\D/g, '');
                                            handleFormDataChange('INN', value);
                                        }}
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
                                            type="text"
                                            name="passwordSeriaNumber"
                                            className="formInput"
                                            placeholder="1234 567890"
                                            maxLength="11"
                                            value={formData.passwordSeriaNumber || ''}
                                            onChange={(e) => handlePassportChange('passwordSeriaNumber', e.target.value)}
                                        />
                                </div>

                                <div className="input-container w-49">
                                    <label htmlFor="dateOfIssue" className="formLabel">Дата выдачи</label>
                                        <input
                                            style={{width: '100%'}}
                                            type="text"
                                            name="dateOfIssue"
                                            className="formInput"
                                            placeholder="01.01.1990"
                                            maxLength="10"
                                            value={formData.dateOfIssue || ''}
                                            onChange={(e) => handleDateChange('dateOfIssue', e.target.value)}
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
                                            setShowCityOptions(false); 
                                        }}
                                        onSelect={(option) => {
                                            setSelectedMaritalStatus(option);
                                            setShowMaritalOptions(false);
                                        }}
                                    />
                                </div>
                            </div>

                            <SpouseTable
                                formData={formData}
                                setFormData={setFormData}
                                isVisible={selectedMaritalStatus === 'Состою в зарегистрированном браке'}
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
                        </div>
                    )}
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