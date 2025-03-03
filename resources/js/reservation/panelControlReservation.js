function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

const bookingId = generateUUID();
const accordions = generateUUID();

const sections = [
    'action-form-card__form-booking',
    'action-form-card__form-accordion',
];
const activePoint = 'action-form-card__form-active';

document.querySelectorAll('.actions-navbar__point').forEach(item => {
    const section = item.getAttribute('data-section');
    console.log(section)
    if (sections[0] === section) {
        document.querySelector('.action-form-card__form-booking').classList.add(activePoint);
        item.insertAdjacentHTML("beforeend", "<div class=\"actions-navbar__active-indicator\"></div>")
    }
});

function openItemFormReservation(key) {
    // TODO: сделать переключатель между вкладками
}

function openOrCloseDropdown(key) {
    // TODO: сделать аккордион для выпадающих списков
}
