import { getCheckbox } from "../../checkbox/Checkbox";
import { black } from "../../colors";
import { getDataFromOption } from "../getDataFromOption";
import { CustomDropdownFilter } from "./CustomDropdownFilter";

export class NonSearchDropdownFilter extends CustomDropdownFilter {

    constructor(buttonId = undefined, defaultElementClass = "names-dropdown item") {
        super(null, buttonId, defaultElementClass);

        document.addEventListener("optionSelected", (event) => this.onActiveOptionsChanged(event));
        document.addEventListener("optionDeselected", (event) => this.onActiveOptionsChanged(event));
    }

    onOptionClicked(option) {
        let event;
        const data = getDataFromOption(option);

        if (!option.className.includes("selected")) {
            event = new Event("optionSelected");
        } else {
            event = new Event("optionDeselected");
        }

        event.filter = this;
        Object.assign(event, data);
        event.option = option;
        const checkboxElement = option.getElementsByClassName("pseudo-checkbox")[0];

        if (checkboxElement) {
            event.checkbox = getCheckbox(checkboxElement.getAttribute("checkboxid"));
        }

        document.dispatchEvent(event);
    }

    onOptionSelected(event) {

    }

    onOptionDeselected(event) {

    }

    onActiveOptionsChanged(event) {
        if (event.filter != this) {
            return;
        }

        this.options.forEach(option => {
            if (option.className.includes("selected") && !this.allowMultiple) {
                const checkboxElement = option.getElementsByClassName("pseudo-checkbox")[0];

                if (checkboxElement) {
                    getCheckbox(checkboxElement.getAttribute("checkboxid")).checked(false);
                }

                option.className = option.className.replace(" selected", "");
                this.onOptionDeselected(event);
                this.activeOptions = 0;
            }

            if (option != event.option) {
                return;
            }

            if (event.type == "optionSelected") {
                option.className += " selected";
                event.checkbox?.checked(true);
                this.onOptionSelected(event);
                this.activeOptions++;
            }

            if (event.type == "optionDeselected") {
                option.className = option.className.replace(" selected", "");
                event.checkbox?.checked(false);
                this.onOptionDeselected(event);

                if (this.activeOptions > 0) {
                    this.activeOptions--;
                }
            }
        });

        if (this.counter == null) {
            return;
        }

        this.counter.textContent = this.activeOptions;

        switch (this.activeOptions) {
            case 0:
                this.counter.style.visibility = "hidden";
                this.title.textContent = this.defaultTitle;
                this.title.style.color = this.defaultTitleColor;
                break;
            case 1:
                this.counter.style.visibility = "hidden";

                if (event.type == "optionSelected") {
                    this.title.textContent = event.displayName;
                } else {
                    //only one should be left for filter
                    this.options.forEach(option => {
                        if (option.className.includes("selected")) {
                            this.title.textContent = option.textContent;
                            return;
                        }
                    });
                }

                this.title.style.color = black;
                break;
            default:
                this.counter.style.visibility = "visible";
                this.title.textContent = this.defaultTitle;
                this.title.style.color = black;
                break;
        }
    }
}