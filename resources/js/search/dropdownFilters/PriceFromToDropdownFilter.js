import { round10 } from "../../decimalAdjust";
import { FromToDropdownFilter } from "./FromToDropdownFilter";

export class PriceFromToDropdownFilter extends FromToDropdownFilter {
    prefixFrom = "от";
    prefixTo = "до";
    postfix = "млн ₽";

    constructor(searchController, buttonId) {
        super(searchController, buttonId);
    }

    addMinValue(addObject) {
        this.searchController.removeItemWithId(addObject.searchid);
        addObject.displayName = `${this.prefixFrom} ${this.formatPrice(addObject.value)} ${this.postfix}`;
        this.searchController.addItem(addObject);
    }

    addMaxValue(addObject) {
        this.searchController.removeItemWithId(addObject.searchid);
        addObject.displayName = `${this.prefixTo} ${this.formatPrice(addObject.value)} ${this.postfix}`;
        this.searchController.addItem(addObject);
    }

    onInputTyped(addObject, input) {
        addObject.filter = this;
        addObject.value = Number.parseFloat(input.value) * 1000000;
        const startSearch = this.searchController.getItemWithId(this.fromSearchId);
        const endSearch = this.searchController.getItemWithId(this.toSearchId);

        if (input == this.inputFrom) {
            if (!endSearch || addObject.value < endSearch.value) {
                this.addMinValue(addObject);
            }
        }

        if (input == this.inputTo) {
            if (!startSearch || addObject.value > startSearch.value) {
                this.addMaxValue(addObject);
            }
        }
    }

    formatPrice(value) {
        return round10(Number.parseFloat(value / 1000000), -1);
    }

    updateTitle() {
        let title = "";
        let hasValue = false;

        const startSearch = this.searchController.getItemWithId(this.fromSearchId);
        const endSearch = this.searchController.getItemWithId(this.toSearchId);

        if (startSearch) {
            const formattedPrice = this.formatPrice(startSearch.value);
            title += `${this.prefixFrom} ${formattedPrice} `;
            hasValue = true;
        }

        if (endSearch) {
            const formattedPrice = this.formatPrice(endSearch.value);
            title += `${this.prefixTo} ${formattedPrice} `;
            hasValue = true;
        }

        if (hasValue) {
            title += this.postfix;
            this.title.textContent = title;
        } else {
            this.title.textContent = this.defaultTitle;
        }
    }
}