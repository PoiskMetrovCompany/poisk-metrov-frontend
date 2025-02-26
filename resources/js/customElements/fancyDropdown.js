export class FancyDropdownCustomElement extends HTMLElement {
    isOpen = false;
    optionList;
    counter;
    placeholder;
    defaultPlaceholderText;

    constructor() {
        super();
    }

    load() {
        this.optionList = this.querySelector("ul[is=option-list]");
        this.counter = this.querySelector(".selected-counter");
        this.placeholder = this.querySelector(".placeholder");
        this.optionList.allowMultiple = new Boolean(this.getAttribute("allowMultiple")).valueOf();
        this.optionList.parentDropdown = this;
        this.defaultPlaceholderText = this.placeholder.textContent.trim();

        this.addEventListener("click", (event) => {
            if (event.target == this) {
                if (!this.isOpen) {
                    this.focus();
                } else {
                    this.blur();
                }
            }
        })

        this.addEventListener("focusin", (event) => {
            if (event.target == this) {
                this.switchState(true);
            }
        });

        this.addEventListener("focusout", (event) => {
            if (event.target == this) {
                this.switchState(false);
            }
        });
    }

    switchState(newState = undefined) {
        if (newState == undefined) {
            newState = !this.isOpen;
        }

        this.setAttribute("open", newState);
        this.optionList.setAttribute("open", newState);

        setTimeout(() => this.isOpen = newState, 200);
    }

    onOptionSelected(customOption) {
        switch (this.optionList.selectedCount) {
            case 0:
                this.counter.textContent = "";
                this.counter.style.visibility = "hidden";
                this.placeholder.textContent = this.defaultPlaceholderText;
                this.placeholder.classList.remove("chosen");

                break;
            case 1:
                if (customOption.isSelected) {
                    this.placeholder.textContent = customOption.textContent.trim();
                } else {
                    this.placeholder.textContent = this.optionList.getFirstSelectedOption().textContent;
                }

                this.placeholder.classList.add("chosen");
                this.counter.textContent = "";
                this.counter.style.visibility = "hidden";
                break;
            default:
                this.placeholder.textContent = this.defaultPlaceholderText;

                this.placeholder.classList.add("chosen");
                this.counter.textContent = this.optionList.selectedCount;
                this.counter.style.visibility = "visible";
                break;
        }

        if (customOption.isSelected) {
            this.onAfterOptionSelected(customOption);

            if (!this.optionList.allowMultiple) {
                this.blur();
            }
        } else {
            this.onAfterOptionDeselected(customOption);
        }
    }

    onAfterOptionSelected(customOption) { }
    onAfterOptionDeselected(customOption) { }

    openDropdown = () => this.switchState(true);
    closeDropdown = () => this.switchState(false);

    connectedCallback() {
        setTimeout(() => this.load());
    }
}