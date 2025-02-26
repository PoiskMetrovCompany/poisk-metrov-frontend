export class MobileToolbar extends HTMLElement {
    constructor() {
        super();
    }

    load() {

    }

    connectedCallback() {
        setTimeout(() => this.load())
    }
}