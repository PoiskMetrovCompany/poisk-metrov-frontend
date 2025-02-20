export class CatalogueFilters extends HTMLElement {
    constructor() {
        super();
    }

    load() {

    }

    connectedCallback() {
        setTimeout(() => this.load());
    }
}