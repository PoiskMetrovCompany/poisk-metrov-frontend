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
