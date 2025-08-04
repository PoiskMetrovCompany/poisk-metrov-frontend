import { isFirstChild } from "../../utils/isFirstChild";

let allContexts = [];

export class NameCategoryButton {
    button;
    context;
    className;

    constructor(button) {
        this.button = button;
        this.context = button.getAttribute("context");
        allContexts.push(this.context);
        this.className = button.className;
        const counter = this.button.getElementsByClassName("filter counter")[0];
        let selected = 0;

        document.addEventListener("searchItemAdded", (event) => {
            if (!event.context) {
                return;
            }

            if (event.context == this.context || this.context == "Все" && allContexts.includes(event.context)) {
                selected++;
                counter.style.display = selected > 0 ? "grid" : "none";
                counter.textContent = selected;
            }
        });

        document.addEventListener("searchItemRemoved", (event) => {
            if (!event.context) {
                return;
            }

            if (event.context == this.context || this.context == "Все" && allContexts.includes(event.context)) {
                selected--;
                counter.style.display = selected > 0 ? "grid" : "none";
                counter.textContent = selected;
            }
        });

        this.button.addEventListener("click", () => this.onClick());
        document.addEventListener("nameCategorySwitched", (event) => this.onNameCategorySwitched(event));

        if (isFirstChild(this.button)) {
            this.button.className += " selected";
        }
    }

    onNameCategorySwitched(event) {
        if (event.context != this.context) {
            this.button.className = this.className;
        } else {
            this.button.className = this.className + " selected";
        }
    }

    onClick() {
        const nameCategorySwitchedEvent = new Event("nameCategorySwitched");
        nameCategorySwitchedEvent.context = this.context;
        document.dispatchEvent(nameCategorySwitchedEvent);
    }
}