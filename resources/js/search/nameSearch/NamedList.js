import { isFirstChildOfType } from "../../utils/isFirstChildOfType";
import { getDataFromOption } from "../getDataFromOption";

export class NamedList {
    context;
    element;
    searchController;
    options = [];
    defaultItemStyle;

    constructor(namesDropdownElement, searchController) {
        this.element = namesDropdownElement;
        this.searchController = searchController;
        this.context = this.element.getAttribute("context");
        document.addEventListener("nameCategorySwitched", (event) => this.onNameCategorySwitched(event));

        if (isFirstChildOfType(this.element)) {
            this.show();
        }

        this.options = Array.from(this.element.getElementsByClassName("names-dropdown item"));
        this.options.forEach(item => this.loadItem(item));

        if (this.options.length > 0) {
            this.defaultItemStyle = this.options[0].style.display;
        }

        document.addEventListener("parameterLoadedFromSearch", (event) => this.onParameterLoadedFromSearch(event));
    }

    onParameterLoadedFromSearch(event) {
        this.options.forEach(option => {
            const optionData = getDataFromOption(option);

            if (optionData.condition == event.condition &&
                optionData.value == event.value &&
                optionData.field == event.field) {

                this.searchController.addItem(optionData);
            }
        });
    }

    loadItem(item) {
        const defaultClassName = item.className;
        const id = item.getAttribute("searchid");
        item.addEventListener("click", () => this.onOptionClicked(item));

        document.addEventListener("searchItemAdded", (event) => {
            if (event.searchid == id) {
                item.className += " selected";
            }
        });

        document.addEventListener("searchItemRemoved", (event) => {
            if (event.searchid == id) {
                item.className = defaultClassName;
            }
        });
    }

    onOptionClicked(item) {
        const addObject = getDataFromOption(item);

        this.searchController.addOrRemoveItemWithId(addObject);
    }

    onNameCategorySwitched(event) {
        if (event.context != this.context) {
            this.hide();
        } else {
            this.show();
        }
    }

    hide() {
        this.element.style.display = "none";
    }

    show() {
        this.element.style.display = "grid";
    }

    filterResults(text) {
        this.options.forEach(item => {
            const itemText = item.getAttribute('displayname').toLowerCase();
            item.style.display = itemText.includes(text) ? this.defaultItemStyle : "none";
        });
    }
}