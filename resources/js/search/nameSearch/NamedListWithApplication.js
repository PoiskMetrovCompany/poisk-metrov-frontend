import { getCheckbox } from "../../checkbox/Checkbox";
import { getDataFromOption } from "../getDataFromOption";

export class NamedListWithApplication {
    allItems = [];
    allItemIds = [];
    allItemData = [];
    currentOptions = [];
    currentOptionIds = [];
    outerCounter;
    innerCounter;
    searchController;
    defaultItemStyle;
    defaultDropdownClass;
    dropdown;

    constructor(smallDropdownElement, searchController) {
        this.searchController = searchController;
        this.dropdown = smallDropdownElement;

        this.defaultDropdownClass = smallDropdownElement.className;
        const dropAllButton = document.getElementById(`${smallDropdownElement.querySelector(".custom-dropdown.base-container").id}-reset`);
        const backButton = document.getElementById(`${smallDropdownElement.querySelector(".custom-dropdown.base-container").id}-back`);
        const textInput = smallDropdownElement.querySelector("input");
        const applyButton = smallDropdownElement.querySelector(".filter.apply").querySelector(".common-button");

        this.outerCounter = smallDropdownElement.querySelector(".filter.counter");
        this.innerCounter = textInput.parentElement.querySelector(".filter.counter");
        this.allItems = Array.from(smallDropdownElement.getElementsByClassName("item"));
        this.allItems.forEach(item => this.loadItem(item));

        if (this.allItems.length > 0) {
            this.defaultItemStyle = this.allItems[0].style.display;
        }

        smallDropdownElement.addEventListener("click", (event) => {
            if (event.target == smallDropdownElement ||
                (event.target.parentElement == smallDropdownElement ||
                    event.target.parentElement.className == "text-with-icon") &&
                !event.target.className.includes("base-container")) {

                if (!smallDropdownElement.className.includes("selected")) {
                    smallDropdownElement.className += " selected";
                } else {
                    this.close();
                }
            }

            event.stopPropagation();
        });

        applyButton.onclick = () => this.onApply();

        document.addEventListener("searchItemAdded", (event) => this.onSearchItemAdded(event));
        document.addEventListener("searchItemRemoved", (event) => this.onSearchItemRemoved(event));

        dropAllButton.onclick = () => this.dropAppliesForDropdown();
        backButton.onclick = () => this.close();

        textInput.addEventListener("input", (event) => this.onInputTextChanged(event));
    }

    close() {
        this.dropdown.className = this.defaultDropdownClass;
    }

    dropAppliesForDropdown() {
        this.allItemData.forEach(itemData => {
            this.searchController.removeItemWithId(itemData.searchid);
        });

        this.allItems.forEach(item => {
            this.removeItemSelection(item);
        })

        this.currentOptionIds = [];
        this.currentOptions = [];
    }

    loadItem(item) {
        const defaultClassName = item.className;
        const itemData = getDataFromOption(item);
        this.allItemData.push(itemData);
        const searchid = itemData.searchid;
        this.allItemIds.push(searchid);
        item.addEventListener("click", () => this.onItemClicked(item));

        document.addEventListener("searchItemAdded", (event) => {
            if (event.searchid == searchid) {
                item.className += " selected";
            }
        });

        document.addEventListener("searchItemRemoved", (event) => {
            if (event.searchid == searchid) {
                item.className = defaultClassName;
            }
        });
    }

    onInputTextChanged(event) {
        const value = event.target.value.toLowerCase();
        this.allItems.forEach(item => item.style.display = (item.textContent.toLowerCase().includes(value) ? this.defaultItemStyle : "none"));
    }

    removeItemSelection(item) {
        const addObject = getDataFromOption(item);
        const checkboxElement = item.getElementsByClassName("pseudo-checkbox")[0];
        let checkbox = null;

        if (checkboxElement) {
            checkbox = getCheckbox(checkboxElement.getAttribute("checkboxid"));
        }

        if (this.currentOptionIds.includes(addObject.searchid)) {
            this.currentOptionIds = this.currentOptionIds.filter(id => id != addObject.searchid);
            this.currentOptions = this.currentOptions.filter(option => option.searchid != addObject.searchid);

            if (checkbox) {
                checkbox.checked(false);
            }
        }

        this.updateCounters();
    }

    addItemSelection(item) {
        const addObject = getDataFromOption(item);
        const checkboxElement = item.getElementsByClassName("pseudo-checkbox")[0];
        let checkbox = null;

        if (checkboxElement) {
            checkbox = getCheckbox(checkboxElement.getAttribute("checkboxid"));
        }

        if (!this.currentOptionIds.includes(addObject.searchid)) {
            this.currentOptionIds.push(addObject.searchid);
            this.currentOptions.push(addObject);

            if (checkbox) {
                checkbox.checked(true);
            }
        }

        this.updateCounters();
    }

    onItemClicked(item) {
        const addObject = getDataFromOption(item);

        if (!this.currentOptionIds.includes(addObject.searchid)) {
            this.addItemSelection(item);
        } else {
            this.removeItemSelection(item);
        }
    }

    onApply() {
        this.allItemData.forEach(itemData => {
            if (!this.currentOptionIds.includes(itemData.searchid)) {
                this.searchController.removeItemWithId(itemData.searchid);
            }
        });

        this.searchController.addItemGroup(this.currentOptions);
        this.close();
    }

    onSearchItemAdded(event) {
        this.allItems.forEach(element => {
            if (event.searchid == element.getAttribute('searchid')) {
                this.addItemSelection(element);
            }
        });

        this.updateCounters();
    }

    onSearchItemRemoved(event) {
        this.allItems.forEach(element => {
            if (event.searchid == element.getAttribute('searchid')) {
                this.removeItemSelection(element);
            }
        });

        this.updateCounters();
    }

    updateCounters() {
        this.updateCounter(this.outerCounter, this.currentOptions.length);
        this.updateCounter(this.innerCounter, this.currentOptions.length);
    }

    updateCounter(counter, value) {
        if (counter) {
            counter.textContent = value;
            counter.style.visibility = value > 0 ? "visible" : "hidden";
        }
    }

    filterResults(text) {
        this.allItems.forEach(item => {
            const itemText = item.getAttribute('displayname').toLowerCase();
            item.style.display = itemText.includes(text) ? this.defaultItemStyle : "none";
        });
    }
}