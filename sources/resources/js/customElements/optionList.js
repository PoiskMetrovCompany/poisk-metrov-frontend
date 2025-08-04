export class OptionListCustomElement extends HTMLUListElement {
    options = [];
    allowMultiple = false;
    lastSelectedOption;
    selectedCount = 0;
    parentDropdown;

    constructor() {
        super();
    }

    load() {
        this.options = Array.from(this.querySelectorAll("li[is=custom-option]"));

        this.options.forEach(option => {
            option.parentOptionList = this;

            if (option.isSelected) {
                this.lastSelectedOption = option;
                this.selectedCount++;
                // this.onOptionSelected(customOption);
            }
        });
    }

    onOptionSelected(customOption) {
        if (!this.allowMultiple &&
            this.lastSelectedOption &&
            this.lastSelectedOption.isSelected &&
            this.lastSelectedOption != customOption) {
            this.lastSelectedOption.deselect();
        }

        this.lastSelectedOption = customOption;

        if (this.lastSelectedOption.isSelected) {
            this.selectedCount++;
        } else {
            this.selectedCount--;
        }

        this.parentDropdown.onOptionSelected(customOption);
    }

    getFirstSelectedOption() {
        return this.options.find(option => option.isSelected);
    }

    connectedCallback() {
        //Так надо чтобы parentOptionList загрузился
        setTimeout(() => this.load())
    }
}