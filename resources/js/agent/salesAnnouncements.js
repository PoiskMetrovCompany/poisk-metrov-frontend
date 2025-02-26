export class SalesAnnouncements extends HTMLElement {
    constructor() {
        super();
    }

    load() {
        const showAll = this.querySelector("header").querySelector("button");
        const section = this.querySelector("section");

        showAll.addEventListener("click", () => {
            section.setAttribute("showall", true);
        });
    }

    connectedCallback() {
        setTimeout(() => this.load());
    }
}