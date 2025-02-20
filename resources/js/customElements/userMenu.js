export class UserMenu extends HTMLElement {
    constructor() {
        super();
    }

    load() {
        const checkbox = document.getElementById("agents-options-toggle");
        const closeButton = this.querySelector("header").querySelector("button");

        closeButton.addEventListener("click", () => this.setAttribute("open", false));

        checkbox.onStatusChanged = (event) => {
            this.setAttribute("clientmode", event.newStatus);
        }
    }

    connectedCallback() {
        setTimeout(() => this.load())
    }
}