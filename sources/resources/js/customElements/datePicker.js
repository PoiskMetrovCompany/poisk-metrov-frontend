import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import { isMobile } from '../utils/isMobile';

export class DatePickerCustomElement extends HTMLElement {
    calendar;
    shown = false;
    selectedDate;

    constructor() {
        super();
    }

    load() {
        const calendarContainer = this.querySelector(".calendar-container");
        const calendarElement = calendarContainer.querySelector("div");
        const openButton = this.querySelector("input[type=button]");

        openButton.addEventListener("click", (event) => {
            if (event.target == openButton) {
                this.switchState(true);
            }
        });

        calendarContainer.addEventListener("click", (event) => {
            if (event.target == calendarContainer) {
                this.switchState(false);
            }
        });

        this.calendar = new Calendar(calendarElement, {
            plugins: [
                interactionPlugin,
                dayGridPlugin
            ],
            locale: 'ru-RU',
            initialView: 'dayGridMonth',
            selectable: true,
        });

        this.calendar.on("dateClick", (event) => {
            openButton.value = this.formatDate(event.date);
            this.selectedDate = event.date;
            this.onDateClick(event);

            if (isMobile()) {
                this.switchState(false);
            }
        });

        const splitDate = openButton.value.split(".");
        this.selectedDate = new Date(splitDate[2], splitDate[1], splitDate[0]);
    }

    switchState(newState = undefined) {
        if (newState == undefined) {
            newState = this.calendar.el.parentElement.getAttribute("shown") == "true";
            newState = !newState;
        }

        this.calendar.el.parentElement.setAttribute("shown", newState);

        if (newState) {
            this.calendar.render();
        }
    }

    formatDate = (date) => `${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}`;

    onDateClick = (event) => { }

    connectedCallback() {
        setTimeout(() => this.load());
    }
}