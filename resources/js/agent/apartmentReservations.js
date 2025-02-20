export class ApartmentReservations extends HTMLElement {
    constructor() {
        super();
    }

    load() {
        
    }

    connectedCallback() {
        setTimeout(() => this.load());
    }
}