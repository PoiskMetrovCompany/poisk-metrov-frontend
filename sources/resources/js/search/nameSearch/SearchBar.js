import { registeredFilters } from "../dropdownFilters/CustomDropdownFilter";
import { NameCategoryButton } from "./NameCategoryButton";
import { NamedList } from "./NamedList";
import { NamedListWithApplication } from "./NamedListWithApplication";

export class SearchBar {
    input;
    searchController;
    searchBar;
    textSearch;
    namesDropdown;
    counter;
    activeOptions = 0;

    inputContainerClass;
    defaultNamesDropdownClass;
    dropdownParentsDisplay;

    dropdownButtons = [];
    nameFilters = [];
    namedListsWithApplication = [];
    allowedContexts = [];
    forms = [];

    constructor(searchController, searchBarId = "search-bar") {
        this.searchBar = document.getElementById(searchBarId);
        this.textSearch = this.searchBar.querySelector(".text-search");
        this.input = this.textSearch.querySelector("input");
        this.counter = this.textSearch.querySelector(".filter.counter");
        this.searchController = searchController;

        this.namesDropdown = document.getElementById("names-filter-dropdown");

        this.inputContainerClass = this.textSearch.className;
        this.defaultNamesDropdownClass = this.namesDropdown.className;
        this.forms = this.textSearch.parentElement.getElementsByTagName("form");

        if (this.forms.length > 0) {
            this.dropdownParentsDisplay = this.forms[0].style.display;
        }

        this.textSearch.addEventListener("focusin", (event) => this.onInputFocus(event));
        this.textSearch.addEventListener("focusout", (event) => this.onInputBlur(event));

        const buttonsForDropdowns = Array.from(this.searchBar.getElementsByClassName("names-dropdown button"));
        const nameFilterDropdowns = Array.from(this.searchBar.getElementsByClassName("names-dropdown container"));
        const dropdownsWithApplication = Array.from(this.searchBar.getElementsByClassName("filter base-container type-specific"));

        buttonsForDropdowns.forEach(button => this.dropdownButtons.push(new NameCategoryButton(button)));
        nameFilterDropdowns.forEach(nameFilterDropdown => this.nameFilters.push(new NamedList(nameFilterDropdown, searchController)));
        dropdownsWithApplication.forEach((smallDropdown) => this.namedListsWithApplication.push(new NamedListWithApplication(smallDropdown, searchController)));

        this.allowedContexts = this.dropdownButtons.map(button => button.context);

        document.addEventListener("searchItemAdded", (event) => this.onSearchItemAdded(event));
        document.addEventListener("searchItemRemoved", (event) => this.onSearchItemRemoved(event));
        document.addEventListener("nameCategorySwitched", (event) => this.onNameCategorySwitched(event));

        this.loadInput();
        this.loadResetButton();
        this.loadCloseButton();
        this.filterResults();
    }

    onNameCategorySwitched(event) {
        if (event.context != "Все") {
            this.input.value = "";
            this.filterResults();
        }
    }

    onInputFocus(event) {
        this.open();
    }

    onInputBlur(event) {
        const leavingParent = !this.textSearch.contains(event.relatedTarget);

        if (leavingParent) {
            this.close();
        }
    }

    open() {
        this.textSearch.className = this.inputContainerClass + " selected";

        if (this.forms.length > 0) {
            this.forms[0].style.display = "none";
        }

        this.input.focus();
        this.namesDropdown.className = this.defaultNamesDropdownClass + " open";
        registeredFilters.forEach(filter => filter.close());
    }

    close() {
        this.textSearch.className = this.inputContainerClass;

        if (this.forms.length > 0) {
            this.forms[0].style.display = this.dropdownParentsDisplay;
        }

        this.namesDropdown.className = this.defaultNamesDropdownClass;
        this.textSearch.blur();
    }

    loadInput() {
        this.input.addEventListener("keyup", ({ key }) => {
            if (key === "Enter") {
                this.searchController.submitSearch();
            }
        });

        this.input.addEventListener("input", () => this.filterResults());
    }

    loadResetButton() {
        const resetFiltersButton = document.getElementById("reset-filters-button");
        resetFiltersButton.onclick = () => this.searchController.clearAll();
    }

    loadCloseButton() {
        const closeButton = Array.from(this.searchBar.getElementsByClassName("names-dropdown close-button"))[0];
        closeButton.addEventListener("click", () => this.close());
    }

    updateCounter(counter, value) {
        if (counter) {
            counter.textContent = value;
            counter.style.visibility = value > 0 ? "visible" : "hidden";
        }
    }

    onSearchItemAdded(event) {
        if (this.allowedContexts.includes(event.context)) {
            this.activeOptions++;
            this.onActiveOptionsChanged(event);
        }
    }

    onSearchItemRemoved(event) {
        if (this.allowedContexts.includes(event.context)) {
            this.activeOptions--;
            this.onActiveOptionsChanged(event);
        }
    }

    onActiveOptionsChanged(event) {
        if (this.counter == null) {
            return;
        }

        this.updateCounter(this.counter, this.activeOptions);
    }

    filterResults() {
        const text = this.input.value.toLowerCase();
        this.nameFilters.forEach(nameFilter => nameFilter.filterResults(text));
        this.namedListsWithApplication.forEach(nameList => nameList.filterResults(text));
    }
}