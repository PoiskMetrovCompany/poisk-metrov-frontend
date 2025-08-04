const elementButtonAddCoBorrower = document.getElementById('btn-add_co_borrower');
const elementDeleteBorrower = document.getElementById('delete_borrower');

/**
 * добавление формы для заполнения "созаёмщика"
 */
elementButtonAddCoBorrower.addEventListener('click', function () {
    document.querySelector('.co-borrower__card-body').style.display = 'block';
});

/**
 * удаление формы "созаёмщика" + очищение полей
 */
elementDeleteBorrower.addEventListener('click', function () {
    document.querySelector('.co-borrower__card-body').style.display = 'none';
    // TODO: очистить все поля
});

const sections = [
    'action-form-card__form-booking',
    'action-form-card__form-accordion',
    'action-form-card__mortgage-solution',
    'action-form-card__mortgage-agreement'
];
const activePoint = 'action-form-card__form-active';


/**
 * Определение базового активного пункта меню брони
 */
document.querySelectorAll('.actions-navbar__point').forEach(item => {
    const section = item.getAttribute('data-section');
    console.log(section)
    if (sections[0] === section) {
        document.querySelector('.action-form-card__form-booking').classList.add(activePoint);
        item.classList.add('actions-navbar__active');
        item.insertAdjacentHTML("beforeend", "<div class=\"actions-navbar__active-indicator\"></div>")
    }
});

/**
 * Прослушивание события клика по меню брони
 */
document.addEventListener('DOMContentLoaded', () => {
    function openItemFormReservation(event) {
        event.preventDefault();
        const clickedElement = event.target;

        if (clickedElement.classList.contains('actions-navbar__point')) {
            const section = clickedElement.getAttribute('data-section');

            document.querySelectorAll('.actions-navbar__point').forEach(item => {
                let activeIndicator = item.querySelector('.actions-navbar__active-indicator');
                if (activeIndicator) {
                    item.removeChild(activeIndicator);
                }
                item.classList.remove('actions-navbar__active');
            });

            clickedElement.insertAdjacentHTML("beforeend", "<div class=\"actions-navbar__active-indicator\"></div>")
            clickedElement.classList.add('actions-navbar__active');
            openInTab(section);
        }
    }

    document.querySelectorAll('.actions-navbar__point').forEach(item => {
        item.addEventListener('click', openItemFormReservation);
    });
});

function openInTab(dataSection) {
    const className = 'action-form-card__form-active';
    const coBorrowerSelector = '.co-borrower';
    const controlActionButton = '.control-action-button';
    const action = document.querySelector('.action-form-card');

    action.querySelectorAll('section').forEach((item) => {
        item.classList.remove(className);
        if (item.classList.contains('action-form-card__form-accordion')) {
            document.querySelector(coBorrowerSelector).style.display = 'none';
            document.querySelector(controlActionButton).style.display = 'none';
        }
    });

    document.querySelector(`.${dataSection}`)?.classList.add(className);

    if (dataSection === 'action-form-card__form-accordion') {
        document.querySelector(coBorrowerSelector).style.display = 'block';
        document.querySelector(controlActionButton).style.display = 'block';
    }
}

/**
 * Обработчик событий для элементов аккордиона
 */
document.addEventListener('click', (event) => {
    const controlElement = event.target.closest('.action-accordion__item-control');

    if (controlElement) {
        const dataControl = controlElement.getAttribute('data-control');
        const sectionAppointmentId = `idx-${dataControl}`;
        const targetElement = document.getElementById(sectionAppointmentId);

        if (targetElement) {
            const currentDisplay = window.getComputedStyle(targetElement).display;

            if (currentDisplay === 'none') {
                controlElement.querySelector('svg').style.rotate = '270deg';
                controlElement.querySelector('svg').style.position = 'relative';
                controlElement.querySelector('svg').style.top = '-0.05em';
                targetElement.style.display = 'flex';
            } else {
                controlElement.querySelector('svg').style.rotate = '0deg';
                controlElement.querySelector('svg').style.position = 'relative';
                controlElement.querySelector('svg').style.top = '0';
                targetElement.style.display = 'none';
            }
        }
    }
});


/**
 * Обработка собыйтия клика по внутреннему списку
 */
document.querySelectorAll('#info__sub-dropdown').forEach((item) => {
    var item = item;
    item.addEventListener('click', function (event) {
        let dropdownId = event.currentTarget.getAttribute('data-sub-dropdown');
        let element = document.getElementById(`idx-${dropdownId}`);

        if (element.style.display === 'block') {
            item.querySelector('svg').style.rotate = '0deg';
            element.style.display = 'none';
        } else {
            item.querySelector('svg').style.rotate = '180deg';
            element.style.display = 'block';
        }
    });
});
