export class CitySelection extends HTMLElement {
    constructor() {
        super();
    }

    load() {
        const calledBy = [
            "current-city-button",
            "current-city-mobile-button"
        ];

        calledBy.forEach((buttonId) => {
            const button = document.getElementById(buttonId);

            button.addEventListener("click", () => this.open());
        });

        const closeButton = this.querySelector("button");
        closeButton.addEventListener("click", () => this.close());
        this.addEventListener("click", (event) => {
            if (event.target == this) {
                this.close();
            }
        });
    }

    open = () => this.setAttribute("open", 1);
    close = () => this.setAttribute("open", 0);
    switch = () => this.setAttribute("open", this.getAttribute("open") == 1);

    connectedCallback() {
        setTimeout(() => this.load());
    }
}