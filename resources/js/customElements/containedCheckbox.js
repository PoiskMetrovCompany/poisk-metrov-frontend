export class ContainedCheckboxCustomElement extends HTMLElement {
    status = false;

    constructor() {
        super();
    }

    load() {
        const pseudoCheckbox = this.querySelector(".pseudo-checkbox");

        this.addEventListener("click", () => {
            this.status = !this.status;

            if (this.status) {
                pseudoCheckbox.classList.add("checked");
            } else {
                pseudoCheckbox.classList.remove("checked");
            }

            const checkedEvent = new Event("signedCheckboxChanged");
            checkedEvent.newStatus = this.status;
            checkedEvent.checkbox = this;
            document.dispatchEvent(checkedEvent);
            this.onStatusChanged(checkedEvent);
        });
    }

    onStatusChanged(event) { }

    connectedCallback() {
        this.load();
    }
}