import { getCheckbox } from "../../checkbox/Checkbox";
import { getDataFromOption } from "../getDataFromOption";
import { wasClickedOutside } from "../../utils/wasClickedOutside";
import { black } from "../../colors";

export const registeredFilters = [];

export class CustomDropdownFilter {
    buttonId = null;

    searchController = null;
    button = null;
    container = null;
    options = null;
    icon = null;

    defaultContainerClass = "";
    defaultFilterClass = "";

    isOpen = false;
    allowMultiple = true;
    sendRequestOnChange = true;

    counter = null;
    activeOptions = 0;
    title = null;
    defaultTitle = null;
    defaultTitleColor;
    addObjectTemplate;

    constructor(searchController, buttonId = undefined, defaultElementClass = "custom-dropdown text-item") {
        if (buttonId != undefined) {
            this.buttonId = buttonId;
        }

        this.searchController = searchController;
        this.button = document.getElementById(this.buttonId);
        this.container = this.button.querySelector(".custom-dropdown.base-container");
        this.counter = this.button.querySelector(".filter.counter");
        this.title = this.button.getElementsByTagName("span")[0];
        this.defaultTitle = this.title.textContent;
        this.defaultTitleColor = window.getComputedStyle(this.title).color;
        this.icon = this.button.querySelector(".arrow-tailless");
        this.defaultContainerClass = this.container.className;
        this.defaultFilterClass = this.button.className;
        this.allowMultiple = this.container.getAttribute("allowMultiple") == '1';

        registeredFilters.push(this);

        this.button.setAttribute("tabindex", -1);

        this.button.addEventListener("focusin", (event) => {
            this.open();
        });

        this.button.addEventListener("click", (event) => {
            if (this.isOpen &&
                (event.target == this.button ||
                    (event.target.parentElement == this.button &&
                        (event.target.tagName == "SPAN" ||
                            event.target.className.includes("icon"))))) {
                this.close();
            }
        });

        this.button.addEventListener("focusout", (event) => {
            if (wasClickedOutside(this.button)) {
                this.close();
            }
        });

        document.addEventListener("filterChanged", (event) => {
            if (event.filterId != this.buttonId) {
                this.close();
            }
        });

        document.addEventListener("searchItemAdded", (event) => {
            if (event.filter == this) {
                this.activeOptions++;
                this.onActiveOptionsChanged(event);

                if (!this.allowMultiple) {
                    this.close();
                }
            }
        });

        document.addEventListener("searchItemRemoved", (event) => {
            if (event.filter == this) {
                this.activeOptions--;
                this.onActiveOptionsChanged(event);
            }
        });

        this.options = Array.from(this.container.getElementsByClassName(defaultElementClass));
        this.options.forEach(option => option.addEventListener("click", () => this.onOptionClicked(option)));

        if (this.options.length > 0) {
            this.addObjectTemplate = getDataFromOption(this.options[0]);
        } else {
            this.button.style.cursor = "not-allowed";
            this.button.style.opacity = 0.5;
        }

        document.addEventListener("parameterLoadedFromSearch", (event) => this.onParameterLoadedFromSearch(event));
    }

    onParameterLoadedFromSearch(event) {
        this.options.forEach(option => {
            const optionData = getDataFromOption(option);

            if (optionData.condition == event.condition &&
                optionData.value == event.value &&
                optionData.field == event.field) {

                this.onOptionClicked(option);
            }
        });
    }

    indexOfOption(option) {
        return Array.from(option.parentElement.children).indexOf(option);
    }

    onOptionClicked(option) {
        const addObject = getDataFromOption(option);
        addObject.filter = this;

        const checkboxElement = option.getElementsByClassName("pseudo-checkbox")[0];

        if (checkboxElement) {
            addObject.checkbox = getCheckbox(checkboxElement.getAttribute("checkboxid"));
        }

        let requestSend = false;

        if (this.searchController.getItemWithId(addObject.searchid)) {
            requestSend = true;
        }

        this.searchController.addOrRemoveItemWithId(addObject);

        if (requestSend && this.sendRequestOnChange) {
            this.searchController.submitSearch();
        }
    }

    onActiveOptionsChanged(event) {
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

                if (event.type == "searchItemAdded") {
                    this.title.textContent = event.displayName;
                } else {
                    //only one should be left for filter
                    const last = this.searchController.searchItems.find(item => item.filter == this);
                    this.title.textContent = last ? last.displayName : this.defaultTitle;
                }

                this.title.style.color = black;
                break;
            default:
                this.counter.style.visibility = "visible";
                this.title.textContent = this.defaultTitle;
                this.title.style.color = black;
                break;
        }

        this.options.forEach(option => {
            const optionData = getDataFromOption(option);

            if (optionData.searchid == event.searchid && optionData.value == event.value) {
                if (event.type == "searchItemAdded") {
                    option.className += " selected";
                }

                if (event.type == "searchItemRemoved") {
                    option.className = option.className.replace(" selected", "");
                }
            }
        });
    }

    open() {
        if (this.options.length == 0) {
            return;
        }

        this.container.classList.add("open");
        this.button.classList.add("selected");
        this.icon.style.rotate = "180deg";
        const event = new Event("filterChanged");
        event.filterId = this.buttonId;
        document.dispatchEvent(event);
        //Чтобы click не срабатывал сразу после нажатия
        setTimeout(() => this.isOpen = true, 300);
    }

    close() {
        this.container.classList.remove("open");
        this.button.classList.remove("selected");
        this.icon.style.rotate = "0deg";
        this.isOpen = false;
        this.button.blur();
    }
}