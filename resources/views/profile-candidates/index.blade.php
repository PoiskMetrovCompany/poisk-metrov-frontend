@extends('profile-candidates.layout.app')

@section('content')
    <section style="flex-wrap: wrap; min-height: auto ;">
        <div class="formRow justify-space-between w-60" >
            <div class="flex-direction-column">
                <h1>Кандидаты</h1>
                <button class = "aButton" id = "checkAll">Выбрать всех</button>
            </div>
            <button id = "filters" aria-label="Нажмите, чтобы открыть фильтры">
                <img src="/img/filters.png" alt="PNG картинка, фильтров">
                Фильтры
            </button>
        </div>
        <table class = "candidatesTable w-60">
            <thead>
            <tr style="border: 0;">
                <th width = "50"></th>
                <th>ФИО Кандидата</th>
                <th>Дата и время</th>
                <th>Вакансия</th>
                <th style="text-align: right;  padding-right: 30px;">Статус</th>
                <th width = "100"></th>
            </tr>
            </thead>
            <tbody id = "candidatesTableBody">
            <tr>
                <td>
                    <label class="custom-checkbox" for="personalData">
                        <input type="checkbox" name="personalData" id="personalData">
                        <span class="checkmark"></span>
                    </label>
                </td>
                <td>
                    Александров Александр Александрович
                </td>
                <td>
                    10.07.2025 14:45
                </td>
                <td>
                    Агент по недвижимости
                </td>
                <td style="display: flex; justify-content: flex-end; margin-right: 20px;">
                    <p id="rejected">Отклонен</p>
                </td>
                <td>
                    <button id = "radactBtn"> <img src="/img/pen.png" alt="Редактировать анкету"></button>
                    <button id = "downloadBtn"> <img src="/img/download.png" alt="Скачать анкету"></button>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="formRow w-60 justify-space-between" style = "margin-top: 2rem;">
            <div class = "left-side">
                <button id="prevBtn" class = "navBtn inactive">Предыдущая</button>
                <div class = "pagination">
                    <button class = "paginationBtn active">1</button>
                    <button class = "paginationBtn">2</button>
                    <button class = "paginationBtn">3</button>
                    <button class = "paginationBtn">...</button>
                    <button class = "paginationBtn">10</button>
                </div>
                <button id="nexBtn" class = "navBtn">Следущая</button>
            </div>
            <div class="download-button-group right-side">
                <button class="download-btn primary">
                    Скачать
                </button>
                <button class="download-btn dropdown-toggle">
                    <span class="format-text">.xlsx</span>
                    <icon class="chevron-down"></icon>
                </button>
                <div class="file-formats-card hide">
                    <div class="format-item">.xlsx</div>
                    <div class="format-item">.pdf</div>
                </div>
            </div>
        </div>
    </section>
    <aside class="calendar-filter-panel" id="calendarPanel">
        <div class="center-card" style = "min-width: 800px; height: 100%; padding-bottom: 50px;">
            <div class="formRow flex-direction-column" style="margin-top: 20px;">
                <div class="custom-select">
                    <div class="select-selected">Диапазон дат</div>
                    <div class="select-items select-hide">
                        <div>Диапазон дат</div>
                        <div>Диапазон месяцев</div>
                        <div>Диапазон годов</div>
                    </div>
                </div>
                <select id="rangeTypeSelect" style="display: none;">
                    <option value="dates">Диапазон дат</option>
                    <option value="months">Диапазон месяцев</option>
                    <option value="years">Диапазон годов</option>
                </select>
            </div>
            <div class="calendar-container">
                <div class="calendar-wrapper">
                    <div class="calendar-header">
                        <span class="nav-arrow" id="prevMonth1">&#8249;</span>
                        <span class="month-year" id="monthYear1">Сентябрь 2022</span>
                        <span class="nav-arrow" id="nextMonth1">&#8250;</span>
                    </div>
                    <table class="calendar" id="calendar1">
                        <thead>
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
                        <tbody></tbody>
                    </table>
                </div>
                <div class="calendar-wrapper">
                    <div class="calendar-header">
                        <span class="nav-arrow" id="prevMonth2">&#8249;</span>
                        <span class="month-year" id="monthYear2">Сентябрь 2024</span>
                        <span class="nav-arrow" id="nextMonth2">&#8250;</span>
                    </div>
                    <table class="calendar" id="calendar2">
                        <thead>
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
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="formRow">
                <h3 style = "text-align: left; padding-left: 10px">Фильтр по статусу</h3>
            </div>
            <div class = "formRow justify-flex-start" id = "filterRow" style="padding-left: 10px; flex-wrap: wrap;">
                <button data-filter="status" data-value="showAll" class = "filterButton">Показать все</button>
                <button data-filter="status" data-value="newForm" class = "filterButton">Новая анкета</button>
                <button data-filter="status" data-value="checked" class = "filterButton">Проверен</button>
                <button data-filter="status" data-value="needRevision" class = "filterButton">Нужна доработка</button>
                <button data-filter="status" data-value="rejected" class = "filterButton">Отклонен</button>
            </div>
            <div class="formRow">
                <h3 style = "text-align: left; padding-left: 10px">Фильтр по вакансии</h3>
            </div>
            <div class = "formRow justify-flex-start" id = "filterRow" style="padding-left: 10px; flex-wrap: wrap;">
                <button data-filter="vacancy" data-value="showAll" class = "filterButton">Показать все</button>
                <button data-filter="vacancy" data-value="estateAgent" class = "filterButton">Агент по недвижимости</button>
                <button data-filter="vacancy" data-value="mortageSpec" class = "filterButton">Ипотечный специалист</button>
                <button data-filter="vacancy" data-value="HR" class = "filterButton">HR</button>
                <button data-filter="vacancy" data-value="lawyer" class = "filterButton" style = "margin-left: 0;">Юрист</button>
                <button data-filter="vacancy" data-value="developer" class = "filterButton">Разработчик</button>
                <button data-filter="vacancy" data-value="designer" class = "filterButton" style = "margin-left: 20px !important;">Дизайнер</button>
            </div>
            <div class = "formRow justify-space-between" style = "margin-top: 0;">
                <button class="formBtn  btn-active" disabled="true">
                    Применить
                </button>
                <button class="formBtn  btn-inactive" disabled="true">
                    Сбросить
                </button>
            </div>
        </div>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const candidates = [
                {
                    id: 1,
                    name: "Александров Александр Александрович",
                    datetime: "10.07.2025 14:45",
                    vacancy: "Агент по недвижимости",
                    status: "Новая анкета",
                    statusID: "new"
                },
                {
                    id: 2,
                    name: "Иванова Мария Сергеевна",
                    datetime: "11.07.2025 10:30",
                    vacancy: "Менеджер по продажам",
                    status: "Проверен",
                    statusID: "checked"
                },
                {
                    id: 3,
                    name: "Петров Петр Петрович",
                    datetime: "12.07.2025 09:15",
                    vacancy: "Юрист",
                    status: "Нужна доработка",
                    statusID: "needRevision"
                },
                {
                    id: 4,
                    name: "Сидорова Анна Владимировна",
                    datetime: "13.07.2025 16:00",
                    vacancy: "Бухгалтер",
                    status: "Отклонен",
                    statusID: "rejected"
                },
                {
                    id: 5,
                    name: "Кузнецов Иван Иванович",
                    datetime: "14.07.2025 12:45",
                    vacancy: "Дизайнер",
                    status: "Новая анкета",
                    statusID: "new"
                },
                {
                    id: 6,
                    name: "Смирнова Ольга Николаевна",
                    datetime: "15.07.2025 11:20",
                    vacancy: "Маркетолог",
                    status: "Проверен",
                    statusID: "checked"
                },
                {
                    id: 7,
                    name: "Васильев Дмитрий Андреевич",
                    datetime: "16.07.2025 13:10",
                    vacancy: "Разработчик",
                    status: "Нужна доработка",
                    statusID: "needRevision"
                },
                {
                    id: 8,
                    name: "Морозова Екатерина Павловна",
                    datetime: "17.07.2025 15:30",
                    vacancy: "HR-менеджер",
                    status: "Отклонен",
                    statusID: "rejected"
                },
                {
                    id: 9,
                    name: "Григорьев Артем Сергеевич",
                    datetime: "18.07.2025 17:05",
                    vacancy: "Аналитик",
                    status: "Проверен",
                    statusID: "checked"
                },
                {
                    id: 10,
                    name: "Зайцева Наталья Викторовна",
                    datetime: "19.07.2025 08:50",
                    vacancy: "Тестировщик",
                    status: "Новая анкета",
                    statusID: "new"
                }
            ];

            // --- Заполнение таблицы ---
            const tbody = document.getElementById('candidatesTableBody');
            tbody.innerHTML = candidates.map((c, idx) => `
        <tr>
            <td>
                <label class="custom-checkbox" for="personalData${c.id}">
                    <input type="checkbox" name="personalData" id="personalData${c.id}">
                    <span class="checkmark"></span>
                </label>
            </td>
            <td>${c.name}</td>
            <td>${c.datetime}</td>
            <td>${c.vacancy}</td>
            <td style="display: flex; justify-content: flex-end; margin-right: 20px;">
                <p id="${c.statusID}">${c.status}</p>
            </td>
            <td>
                <button id="radactBtn${c.id}"><img src="/img/pen.png" alt="Редактировать анкету"></button>
                <button id="downloadBtn${c.id}"><img src="/img/download.png" alt="Скачать анкету"></button>
            </td>
        </tr>
    `).join('');
            const dropdownToggle = document.querySelector('.download-btn.dropdown-toggle');
            const dropdownMenu = document.querySelector('.file-formats-card');
            const formatText = document.querySelector('.format-text');
            const formatItems = document.querySelectorAll('.format-item');

            dropdownToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hide');
            });

            document.addEventListener('click', function() {
                if (!dropdownMenu.classList.contains('hide')) {
                    dropdownMenu.classList.add('hide');
                }
            });

            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            formatItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    formatText.textContent = item.textContent;
                    dropdownMenu.classList.add('hide');
                });
            });

            // --- КАЛЕНДАРЬ И ФИЛЬТРЫ ---
            let filterArr = [];
            let startDate = null;
            let endDate = null;
            let currentRangeType = 'dates';
            let calendar1Date = new Date(2022, 8, 1);
            let calendar2Date = new Date(2024, 8, 1);
            const monthNames = [
                'Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь',
                'Июль', 'Авг', 'Сент', 'Окт', 'Нояб', 'Дек'
            ];
            const calendar1 = document.getElementById('calendar1').querySelector('tbody');
            const calendar2 = document.getElementById('calendar2').querySelector('tbody');
            const monthYear1 = document.getElementById('monthYear1');
            const monthYear2 = document.getElementById('monthYear2');
            const calendarContainer = document.querySelector('.calendar-container');
            const prevMonth1 = document.getElementById('prevMonth1');
            const nextMonth1 = document.getElementById('nextMonth1');
            const prevMonth2 = document.getElementById('prevMonth2');
            const nextMonth2 = document.getElementById('nextMonth2');

            function formatDateForDisplay(date) {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                if (currentRangeType === 'dates') return `${day}.${month}.${year}`;
                if (currentRangeType === 'months') return `${month}.${year}`;
                if (currentRangeType === 'years') return `${year}`;
                return `${day}.${month}.${year}`;
            }
            function updateNavigationArrows() {
                if (currentRangeType === 'years') {
                    prevMonth1.style.display = 'none';
                    nextMonth1.style.display = 'none';
                    prevMonth2.style.display = 'none';
                    nextMonth2.style.display = 'none';
                } else {
                    prevMonth1.style.display = 'inline';
                    nextMonth1.style.display = 'inline';
                    prevMonth2.style.display = 'inline';
                    nextMonth2.style.display = 'inline';
                }
            }
            function updateTableHeaders() {
                const headers1 = document.getElementById('calendar1').querySelector('thead');
                const headers2 = document.getElementById('calendar2').querySelector('thead');
                if (currentRangeType === 'dates') {
                    headers1.style.display = '';
                    headers2.style.display = '';
                } else {
                    headers1.style.display = 'none';
                    headers2.style.display = 'none';
                }
            }
            initCustomSelect();
            function initCustomSelect() {
                const selectSelected = document.querySelector('.select-selected');
                const selectItems = document.querySelector('.select-items');
                const hiddenSelect = document.getElementById('rangeTypeSelect');
                selectSelected.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeAllSelect(this);
                    selectItems.classList.toggle('select-hide');
                    this.classList.toggle('select-arrow-active');
                });
                const options = selectItems.querySelectorAll('div');
                options.forEach(option => {
                    option.addEventListener('click', function() {
                        const previousSelected = selectItems.querySelector('.same-as-selected');
                        if (previousSelected) previousSelected.classList.remove('same-as-selected');
                        this.classList.add('same-as-selected');
                        selectSelected.innerHTML = this.innerHTML;
                        const selectedText = this.innerHTML;
                        if (selectedText === 'Диапазон дат') {
                            hiddenSelect.value = 'dates';
                            currentRangeType = 'dates';
                            calendarContainer.classList.remove("calendar-spacing")
                        } else if (selectedText === 'Диапазон месяцев') {
                            hiddenSelect.value = 'months';
                            currentRangeType = 'months';
                            calendarContainer.classList.remove("calendar-spacing")
                        } else if (selectedText === 'Диапазон годов') {
                            hiddenSelect.value = 'years';
                            currentRangeType = 'years';
                            calendarContainer.classList.remove("calendar-spacing")
                        }
                        startDate = null;
                        endDate = null;
                        updateCalendars();
                        updateNavigationArrows();
                        updateTableHeaders();
                        updateMarkerStyles();
                        selectItems.classList.add('select-hide');
                        selectSelected.classList.remove('select-arrow-active');
                        logDateRange();
                    });
                });
                options[0].classList.add('same-as-selected');
                hiddenSelect.value = 'dates';
            }
            function closeAllSelect(elmnt) {
                const items = document.getElementsByClassName('select-items');
                const selected = document.getElementsByClassName('select-selected');
                for (let i = 0; i < selected.length; i++) {
                    if (elmnt !== selected[i] && elmnt !== selected[i].nextSibling) {
                        selected[i].classList.remove('select-arrow-active');
                        if (items[i]) items[i].classList.add('select-hide');
                    }
                }
            }
            document.addEventListener('click', closeAllSelect);
            prevMonth1.addEventListener('click', () => {
                if (currentRangeType === 'dates') {
                    calendar1Date.setMonth(calendar1Date.getMonth() - 1);
                    updateCalendar1();
                } else if (currentRangeType === 'months') {
                    calendar1Date.setFullYear(calendar1Date.getFullYear() - 1);
                    updateCalendars();
                }
            });
            nextMonth1.addEventListener('click', () => {
                if (currentRangeType === 'dates') {
                    calendar1Date.setMonth(calendar1Date.getMonth() + 1);
                    updateCalendar1();
                } else if (currentRangeType === 'months') {
                    calendar1Date.setFullYear(calendar1Date.getFullYear() + 1);
                    updateCalendars();
                }
            });
            prevMonth2.addEventListener('click', () => {
                if (currentRangeType === 'dates') {
                    calendar2Date.setMonth(calendar2Date.getMonth() - 1);
                    updateCalendar2();
                } else if (currentRangeType === 'months') {
                    calendar2Date.setFullYear(calendar2Date.getFullYear() - 1);
                    updateCalendars();
                }
            });
            nextMonth2.addEventListener('click', () => {
                if (currentRangeType === 'dates') {
                    calendar2Date.setMonth(calendar2Date.getMonth() + 1);
                    updateCalendar2();
                } else if (currentRangeType === 'months') {
                    calendar2Date.setFullYear(calendar2Date.getFullYear() + 1);
                    updateCalendars();
                }
            });
            function updateCalendars() {
                if (currentRangeType === 'months') {
                    monthYear1.textContent = calendar1Date.getFullYear();
                    monthYear2.textContent = calendar2Date.getFullYear();
                    generateMonthsCalendar(calendar1Date.getFullYear(), calendar1);
                    generateMonthsCalendar(calendar2Date.getFullYear(), calendar2);
                } else if (currentRangeType === 'years') {
                    monthYear1.textContent = 'ОТ';
                    monthYear2.textContent = 'ДО';
                    generateYearsCalendar(calendar1Date.getFullYear(), calendar1);
                    generateYearsCalendar(calendar2Date.getFullYear(), calendar2);
                } else {
                    updateCalendar1();
                    updateCalendar2();
                }
                updateCalendarStyles();
            }
            function updateCalendar1() {
                const month = calendar1Date.getMonth();
                const year = calendar1Date.getFullYear();
                monthYear1.textContent = `${monthNames[month]} ${year}`;
                generateCalendar(year, month, calendar1);
                updateCalendarStyles();
            }
            function updateCalendar2() {
                const month = calendar2Date.getMonth();
                const year = calendar2Date.getFullYear();
                monthYear2.textContent = `${monthNames[month]} ${year}`;
                generateCalendar(year, month, calendar2);
                updateCalendarStyles();
            }
            function generateCalendar(year, month, tbody) {
                tbody.innerHTML = '';
                const firstDay = new Date(year, month, 1).getDay();
                const startDayIndex = firstDay === 0 ? 6 : firstDay - 1;
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                let date = 1;
                for (let i = 0; i < 6; i++) {
                    const row = document.createElement('tr');
                    let isRowEmpty = true;
                    for (let j = 0; j < 7; j++) {
                        const cell = document.createElement('td');
                        if ((i === 0 && j < startDayIndex) || date > daysInMonth) {
                            cell.textContent = '';
                        } else {
                            const dayStr = String(date);
                            cell.innerHTML = `<span class="day-number">${dayStr}</span>`;
                            cell.dataset.date = `${year}-${String(month + 1).padStart(2, '0')}-${dayStr.padStart(2, '0')}`;
                            cell.dataset.year = year;
                            cell.dataset.month = month;
                            cell.dataset.day = date;
                            cell.addEventListener('click', handleDateClick);
                            date++;
                            isRowEmpty = false;
                        }
                        row.appendChild(cell);
                    }
                    if (!isRowEmpty || i === 0) tbody.appendChild(row);
                    if (date > daysInMonth) break;
                }
            }
            function generateMonthsCalendar(year, tbody) {
                tbody.innerHTML = '';
                for (let i = 0; i < 3; i++) {
                    const row = document.createElement('tr');
                    for (let j = 0; j < 4; j++) {
                        const monthIndex = i * 4 + j;
                        if (monthIndex >= 12) break;
                        const cell = document.createElement('td');
                        cell.colSpan = 2;
                        if (j === 3 && i === 2) cell.colSpan = 3;
                        cell.textContent = monthNames[monthIndex];
                        cell.dataset.date = `${year}-${String(monthIndex + 1).padStart(2, '0')}-01`;
                        cell.dataset.year = year;
                        cell.dataset.month = monthIndex;
                        cell.addEventListener('click', handleDateClick);
                        row.appendChild(cell);
                    }
                    if (i === 2) {
                        while (row.children.length < 3) {
                            const emptyCell = document.createElement('td');
                            emptyCell.colSpan = 2;
                            row.appendChild(emptyCell);
                        }
                    }
                    tbody.appendChild(row);
                }
            }
            function generateYearsCalendar(year, tbody) {
                tbody.innerHTML = '';
                const startYear = 2020;
                const endYear = 2025;
                const totalYears = endYear - startYear + 1;
                for (let i = 0; i < Math.ceil(totalYears / 3); i++) {
                    const row = document.createElement('tr');
                    for (let j = 0; j < 3; j++) {
                        const yearIndex = startYear + i * 3 + j;
                        if (yearIndex > endYear) break;
                        const cell = document.createElement('td');
                        cell.colSpan = 3;
                        if (j === 2) cell.colSpan = 1;
                        cell.textContent = yearIndex;
                        cell.dataset.date = `${yearIndex}-01-01`;
                        cell.dataset.year = yearIndex;
                        cell.addEventListener('click', handleDateClick);
                        row.appendChild(cell);
                    }
                    if (i === Math.ceil(totalYears / 3) - 1) {
                        while (row.children.length < 3) {
                            const emptyCell = document.createElement('td');
                            emptyCell.colSpan = 3;
                            row.appendChild(emptyCell);
                        }
                    }
                    tbody.appendChild(row);
                }
            }
            function handleDateClick(event) {
                const cell = event.currentTarget;
                const dateStr = cell.dataset.date;
                const selectedDate = new Date(dateStr);
                if (currentRangeType === 'dates') {
                    handleDateSelection(selectedDate);
                } else if (currentRangeType === 'months') {
                    handleMonthSelection(selectedDate);
                } else if (currentRangeType === 'years') {
                    handleYearSelection(selectedDate);
                }
                updateCalendarStyles();
                logDateRange();
            }
            function handleDateSelection(selectedDate) {
                if (startDate && startDate.getTime() === selectedDate.getTime()) {
                    startDate = null;
                    endDate = null;
                } else if (!startDate || (startDate && endDate)) {
                    startDate = selectedDate;
                    endDate = null;
                } else if (selectedDate < startDate) {
                    endDate = startDate;
                    startDate = selectedDate;
                } else {
                    endDate = selectedDate;
                }
            }
            function handleMonthSelection(selectedDate) {
                const selectedMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
                if (startDate && startDate.getTime() === selectedMonth.getTime()) {
                    startDate = null;
                    endDate = null;
                } else if (!startDate || (startDate && endDate)) {
                    startDate = selectedMonth;
                    endDate = null;
                } else if (selectedMonth < startDate) {
                    endDate = startDate;
                    startDate = selectedMonth;
                } else {
                    endDate = selectedMonth;
                }
            }
            function handleYearSelection(selectedDate) {
                const selectedYear = new Date(selectedDate.getFullYear(), 0, 1);
                if (startDate && startDate.getTime() === selectedYear.getTime()) {
                    startDate = null;
                    endDate = null;
                } else if (!startDate || (startDate && endDate)) {
                    startDate = selectedYear;
                    endDate = null;
                } else if (selectedYear < startDate) {
                    endDate = startDate;
                    startDate = selectedYear;
                } else {
                    endDate = selectedYear;
                }
            }
            function updateCalendarStyles() {
                document.querySelectorAll('.calendar td').forEach(cell => {
                    cell.classList.remove('in-range', 'start-date', 'end-date', 'start-date-bg', 'end-date-bg');
                });
                if (startDate) {
                    if (currentRangeType === 'dates') {
                        updateDateStyles();
                    } else if (currentRangeType === 'months') {
                        updateMonthStyles();
                    } else if (currentRangeType === 'years') {
                        updateYearStyles();
                    }
                }
            }
            function updateDateStyles() {
                const startCells = document.querySelectorAll(`td[data-date="${formatDate(startDate)}"]`);
                startCells.forEach(cell => {
                    cell.classList.add('start-date');
                    if (endDate) cell.classList.add('start-date-bg');
                });
                if (endDate) {
                    const endCells = document.querySelectorAll(`td[data-date="${formatDate(endDate)}"]`);
                    endCells.forEach(cell => {
                        cell.classList.add('end-date');
                        cell.classList.add('end-date-bg');
                    });
                    const allDateCells = document.querySelectorAll('td[data-date]');
                    allDateCells.forEach(cell => {
                        const cellDate = new Date(cell.dataset.date);
                        if (cellDate > startDate && cellDate < endDate) {
                            cell.classList.add('in-range');
                        }
                    });
                }
            }
            function updateMonthStyles() {
                const startMonth = startDate.getMonth();
                const startYear = startDate.getFullYear();
                const startCells = document.querySelectorAll(`td[data-month="${startMonth}"][data-year="${startYear}"]`);
                startCells.forEach(cell => {
                    cell.classList.add('start-date');
                    if (endDate) cell.classList.add('start-date-bg');
                });
                if (endDate) {
                    const endMonth = endDate.getMonth();
                    const endYear = endDate.getFullYear();
                    const endCells = document.querySelectorAll(`td[data-month="${endMonth}"][data-year="${endYear}"]`);
                    endCells.forEach(cell => {
                        cell.classList.add('end-date');
                        cell.classList.add('end-date-bg');
                    });
                    const allDateCells = document.querySelectorAll('td[data-date]');
                    allDateCells.forEach(cell => {
                        const cellDate = new Date(cell.dataset.date);
                        const cellMonth = new Date(cellDate.getFullYear(), cellDate.getMonth(), 1);
                        if (cellMonth > startDate && cellMonth < endDate) {
                            cell.classList.add('in-range');
                        }
                    });
                }
            }
            function updateYearStyles() {
                const startYear = startDate.getFullYear();
                const startCells = document.querySelectorAll(`td[data-year="${startYear}"]`);
                startCells.forEach(cell => {
                    cell.classList.add('start-date');
                    if (endDate) cell.classList.add('start-date-bg');
                });
                if (endDate) {
                    const endYear = endDate.getFullYear();
                    const endCells = document.querySelectorAll(`td[data-year="${endYear}"]`);
                    endCells.forEach(cell => {
                        cell.classList.add('end-date');
                        cell.classList.add('end-date-bg');
                    });
                    const allDateCells = document.querySelectorAll('td[data-date]');
                    allDateCells.forEach(cell => {
                        const cellYear = parseInt(cell.dataset.year);
                        if (cellYear > startYear && cellYear < endYear) {
                            cell.classList.add('in-range');
                        }
                    });
                }
            }
            function updateMarkerStyles() {
                const existingStyle = document.getElementById('dynamic-marker-styles');
                if (existingStyle) existingStyle.remove();
                const style = document.createElement('style');
                style.id = 'dynamic-marker-styles';
                let markerStyles = '';
                if (currentRangeType === 'dates') {
                    markerStyles = `
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
            `;
                } else if (currentRangeType === 'months' || currentRangeType === 'years') {
                    markerStyles = `
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
                    border-radius: 28px;
                    z-index: -1;
                }
            `;
                }
                style.textContent = markerStyles;
                document.head.appendChild(style);
            }
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
            function initCalendars() {
                updateCalendars();
                updateNavigationArrows();
                updateTableHeaders();
                updateMarkerStyles();
            }
            initCalendars();

            // --- ОТКРЫТИЕ/ЗАКРЫТИЕ ПАНЕЛИ КАЛЕНДАРЯ ---
            const calendarPanel = document.getElementById('calendarPanel');
            const filtersBtn = document.getElementById('filters');
            filtersBtn.addEventListener('click', function() {
                calendarPanel.classList.add('open');
                document.body.style.overflow = 'hidden';
            });
            document.addEventListener('mousedown', function(e) {
                if (
                    calendarPanel.classList.contains('open') &&
                    !calendarPanel.contains(e.target) &&
                    e.target !== filtersBtn
                ) {
                    calendarPanel.classList.remove('open');
                    document.body.style.overflow = '';
                }
            });

            let selectedFilters = {
                status: [],
                vacancy: [],
                dateRange: {
                    type: null, // dates | months | years
                    start: null,
                    end: null
                }
            };


            Array.from(document.getElementsByClassName("filterButton")).forEach(el => {
                el.addEventListener('click', function(){
                    const filter = this.dataset.filter;
                    const value = this.dataset.value;
                    this.classList.toggle("active");

                    if (filter === 'status' || filter === 'vacancy') {
                        if (this.classList.contains('active')) {
                            if (!selectedFilters[filter].includes(value)) {
                                selectedFilters[filter].push(value);
                            }
                        } else {
                            selectedFilters[filter] = selectedFilters[filter].filter(v => v !== value);
                        }
                    }
                });
            });

            // Отслеживание выбранного диапазона дат
            function updateDateRangeFilter() {
                selectedFilters.dateRange.type = currentRangeType;
                selectedFilters.dateRange.start = startDate ? new Date(startDate) : null;
                selectedFilters.dateRange.end = endDate ? new Date(endDate) : null;
            }

            const applyBtn = document.querySelector('.formBtn.btn-active');
            applyBtn.disabled = false;

            applyBtn.addEventListener('click', function() {
                updateDateRangeFilter();

                const params = new URLSearchParams();


                if (selectedFilters.dateRange.start) {
                    params.append('dateType', selectedFilters.dateRange.type);
                    params.append('dateStart', formatDateForUrl(selectedFilters.dateRange.start, selectedFilters.dateRange.type));
                }
                if (selectedFilters.dateRange.end) {
                    params.append('dateEnd', formatDateForUrl(selectedFilters.dateRange.end, selectedFilters.dateRange.type));
                }


                if (selectedFilters.status.length > 0) {
                    params.append('status', selectedFilters.status.join(','));
                }


                if (selectedFilters.vacancy.length > 0) {
                    params.append('vacancy', selectedFilters.vacancy.join(','));
                }


                window.location.search = params.toString();
            });

            function formatDateForUrl(date, type) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                if (type === 'dates') return `${year}-${month}-${day}`;
                if (type === 'months') return `${year}-${month}`;
                if (type === 'years') return `${year}`;
                return '';
            }
        });
    </script>
@endsection
