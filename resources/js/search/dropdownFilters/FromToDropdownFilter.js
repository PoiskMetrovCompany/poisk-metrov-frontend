import { getDataFromOption } from "../getDataFromOption";
import { CustomDropdownFilter } from "./CustomDropdownFilter";

export class FromToDropdownFilter extends CustomDropdownFilter {
    inputFrom;
    inputTo;
    fromScrolls;
    toScrolls;
    fromSearchId;
    toSearchId;

    prefixFrom = "от";
    prefixTo = "до";
    postfix = "";

    constructor(searchController, buttonId) {
        super(searchController, buttonId);

        const searchBarInputs = Array.from(this.button.getElementsByClassName("price-dropdown title"));
        this.inputFrom = searchBarInputs[0];
        this.inputTo = searchBarInputs[1];

        const eventNames = ["input"];

        eventNames.forEach(eventName => {
            this.inputFrom.addEventListener(eventName, (event) => this.onInputFromTyped(event));
            this.inputTo.addEventListener(eventName, (event) => this.onInputToTyped(event));
        });

        document.addEventListener("searchItemAdded", (event) => this.onSearchItemAdded(event));
        document.addEventListener("searchItemRemoved", (event) => this.onSearchItemRemoved(event));

        const scrolls = this.container.getElementsByClassName("price-dropdown container");
        this.fromScrolls = scrolls[0];
        this.toScrolls = scrolls[1];
        this.fromSearchId = this.fromScrolls.firstElementChild.getAttribute("searchid");
        this.toSearchId = this.toScrolls.firstElementChild.getAttribute("searchid");

        this.addObjectTemplate = getDataFromOption(this.toScrolls.firstElementChild);
    }

    onSearchItemAdded(event) {
        this.updateTitleFromEvent(event);
    }

    onSearchItemRemoved(event) {
        this.updateTitleFromEvent(event);

        if (event.searchid == this.fromSearchId && this.inputFrom.value == event.value) {
            this.inputFrom.value = "";
        }

        if (event.searchid == this.toSearchId && this.inputTo.value == event.value) {
            this.inputTo.value = "";
        }
    }

    updateTitleFromEvent(event) {
        if (event.searchid == this.fromSearchId || event.searchid == this.toSearchId) {
            this.updateTitle();
        }
    }

    onInputFromTyped(event) {
        this.filterInput(this.inputFrom);

        if (!this.inputFrom.value) {
            this.searchController.removeItemWithId(this.fromSearchId);
            return;
        }

        const addObject = {};
        Object.assign(addObject, this.addObjectTemplate);
        addObject.condition = ">=";
        addObject.searchid = this.fromSearchId;
        this.onInputTyped(addObject, this.inputFrom);
    }

    onInputToTyped(event) {
        this.filterInput(this.inputTo);

        if (!this.inputTo.value) {
            this.searchController.removeItemWithId(this.toSearchId);
            return;
        }

        const addObject = {};
        Object.assign(addObject, this.addObjectTemplate);
        addObject.condition = "<=";
        addObject.searchid = this.toSearchId;
        this.onInputTyped(addObject, this.inputTo);
    }

    onInputTyped(addObject, input) {
        const startSearch = this.searchController.getItemWithId(this.fromSearchId);
        const endSearch = this.searchController.getItemWithId(this.toSearchId);

        if (input == this.inputFrom) {
            if (endSearch && Number.parseInt(input.value) > Number.parseInt(endSearch.value)) {
                input.value = input.value.toString().substring(1);
            }
        }

        addObject.filter = this;
        addObject.value = Number.parseInt(input.value);

        if (input == this.inputFrom) {
            if (!endSearch || Number.parseInt(addObject.value) < Number.parseInt(endSearch.value)) {
                this.addMinValue(addObject);
            }
        }

        if (input == this.inputTo) {
            if (!startSearch || Number.parseInt(addObject.value) > Number.parseInt(startSearch.value)) {
                this.addMaxValue(addObject);
            }
        }
    }

    filterInput(input) {
        const maxSymbols = 4;
        const split = input.value.toString().split('');
        let goodSymbols = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
        const filtered = [];

        split.forEach((sym, i) => {
            if (goodSymbols.includes(sym) && i < maxSymbols) {
                filtered.push(sym);
            }
        });

        input.value = filtered.join('');
    }

    onOptionClicked(option) {
        const addObject = getDataFromOption(option);
        addObject.value = Number.parseFloat(addObject.value);
        addObject.filter = this;
        const startSearch = this.searchController.getItemWithId(this.fromSearchId);
        const endSearch = this.searchController.getItemWithId(this.toSearchId);

        if (option.parentElement == this.fromScrolls) {
            if (!endSearch || addObject.value < endSearch.value) {
                this.addMinValue(addObject);
            }
        }

        if (option.parentElement == this.toScrolls) {
            if (!startSearch || addObject.value > startSearch.value) {
                this.addMaxValue(addObject);
            }
        }
    }

    addMinValue(addObject) {
        this.searchController.removeItemWithId(addObject.searchid);
        addObject.displayName = `${this.prefixFrom} ${addObject.value} ${this.postfix}`;
        this.searchController.addItem(addObject);
    }

    addMaxValue(addObject) {
        this.searchController.removeItemWithId(addObject.searchid);
        addObject.displayName = `${this.prefixTo} ${addObject.value} ${this.postfix}`;
        this.searchController.addItem(addObject);
    }

    updateTitle() {
        let title = this.defaultTitle;
        let hasValue = false;

        const startSearch = this.searchController.getItemWithId(this.fromSearchId);
        const endSearch = this.searchController.getItemWithId(this.toSearchId);

        if (startSearch) {
            title += ` от ${startSearch.value} `;
            hasValue = true;
        }

        if (endSearch) {
            title += ` до ${endSearch.value} `;
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