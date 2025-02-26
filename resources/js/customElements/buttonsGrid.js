export class ButtonsGridCustomElement extends HTMLElement {
    allowMultiple;
    allowDeselect;
    buttons = [];

    constructor() {
        super();
    }

    load() {
        this.buttons = this.querySelectorAll("button");
        this.allowMultiple = this.getAttribute("allowMultiple") == 1;
        this.allowDeselect = this.getAttribute("allowDeselect") == 1;

        this.buttons.forEach(button => {
            button.addEventListener("click", () => {
                this.onButtonClicked(button);
            });
        });
    }

    onButtonClicked(button, buttonSelectionStatus = undefined, ignoreDeselectForbid = false) {
        const selectedAttribute = button.getAttribute("selected");
        const cachedSelectionStatus = selectedAttribute == "true";

        if (cachedSelectionStatus && !this.allowDeselect && !ignoreDeselectForbid) {
            return;
        }

        if (buttonSelectionStatus === undefined) {
            buttonSelectionStatus = !cachedSelectionStatus;
        } else if (buttonSelectionStatus == cachedSelectionStatus) {
            return;
        }

        if (!this.allowMultiple) {
            this.buttons.forEach(otherButton => {
                if (otherButton != button) {
                    this.onButtonClicked(otherButton, false, true);
                }
            });
        }

        button.setAttribute("selected", buttonSelectionStatus);

        const clickedEvent = new Event("buttonGridClicked");
        clickedEvent.newStatus = buttonSelectionStatus;
        clickedEvent.button = this;
        document.dispatchEvent(clickedEvent);
        this.onStatusChanged(clickedEvent);
    }

    onStatusChanged(event) { }

    connectedCallback() {
        this.load();
    }
}