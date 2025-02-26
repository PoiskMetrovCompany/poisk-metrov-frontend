export class OptionCustomElement extends HTMLLIElement {
    isSelected = false;
    parentOptionList;

    constructor() {
        super();
    }

    load() {
        this.addEventListener("click", (event) => this.switchState());
        this.isSelected = this.getAttribute("selected") == "true";

        if (this.isSelected) {
            this.select();
        }
    }

    switchState(newState = undefined) {
        if (newState == undefined) {
            newState = !this.isSelected;
        }

        const pseudoCheckbox = this.querySelector(".pseudo-checkbox");
        this.isSelected = newState;

        if (this.isSelected) {
            pseudoCheckbox.classList.add("checked");
        } else {
            pseudoCheckbox.classList.remove("checked");
        }

        const optionSelectedEvent = new Event("customOptionStatusSelected");
        optionSelectedEvent.newStatus = this.isSelected;
        optionSelectedEvent.checkbox = this;
        this.setAttribute("selected", this.isSelected);
        document.dispatchEvent(optionSelectedEvent);
        this.onStatusChanged(optionSelectedEvent);
    }

    select = () => this.switchState(true);
    deselect = () => this.switchState(false);

    onStatusChanged(event) {
        this.parentOptionList.onOptionSelected(this);
    }

    connectedCallback() {
        setTimeout(() => this.load());
    }
}