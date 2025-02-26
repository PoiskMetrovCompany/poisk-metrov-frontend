export class EstateCategories extends HTMLElement {
    constructor() {
        super();
    }

    load() {
        
    }

    connectedCallback() {
        setTimeout(() => this.load());
    }
}