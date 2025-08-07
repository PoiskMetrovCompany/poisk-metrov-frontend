export class SearchBarCustomElement extends HTMLElement {
    constructor() {
        super();
    }

    load() {
        this.input = this.querySelector("input");
        this.input.addEventListener("input", () => (event) => this.onInput(event));
        this.input.addEventListener("focus", () => {
            this.setAttribute("selected", true);
        });
        this.input.addEventListener("blur", () => {
            this.setAttribute("selected", false);
        });
    }

    onInput(event) { }

    connectedCallback() {
        this.load();
    }
}