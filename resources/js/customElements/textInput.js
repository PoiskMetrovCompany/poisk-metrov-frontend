export class TextInputCustomElement extends HTMLElement {
    clearButton;
    input;
    isRequired = false;
    textOnly = false;

    constructor() {
        super();
    }

    load() {
        this.input = this.querySelector("input");
        this.isRequired = this.input.getAttribute("required") != null;
        this.textOnly = this.input.getAttribute("textonly") != null;
        this.input.addEventListener("input", () => this.updateClearButtonVisibility());
        this.input.addEventListener("focus", () => this.updateClearButtonVisibility());
        this.input.addEventListener("blur", () => {
            this.updateClearButtonVisibility();

            if (this.input != document.activeElement) {
                ///TODO: fix for phone input
                // this.clearButton.style.display = "none";
            }
        });

        if (this.textOnly) {
            this.input.addEventListener("input", (event) => {
                const split = event.target.value.split('');
                const clean = [];
                const allowedCharacters = ['-', ' '];

                split.forEach(character => {
                    if (/^[a-zA-Z]+$/.test(character) || /^[а-яА-Я]+$/.test(character) || allowedCharacters.includes(character)) {
                        clean.push(character);
                    }
                });

                event.target.value = clean.join('');
            });
        }

        this.clearButton = this.querySelector(".action-close");
        this.clearButton?.addEventListener("click", () => this.clear());
    }

    updateClearButtonVisibility() {
        this.clearButton.style.display = (this.input.value.length > 0) ? "block" : "none";
    }

    isValid() {
        let isValid = this.input.value.length > 0 || !this.isRequired;
        this.setAttribute("invalid", !isValid);

        return isValid;
    }

    clear() {
        this.input.value = "";
        this.updateClearButtonVisibility();
    }

    connectedCallback() {
        this.load();
    }
}