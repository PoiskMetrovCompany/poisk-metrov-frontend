import { PriceRange } from "../search/PriceRange";
import { SearchBar } from "../search/nameSearch/SearchBar";
import { SearchController } from "../search/SearchController";
import { SearchItemBubbles } from "../search/SearchItemBubbles";
import { SearchRange } from "../search/SearchRange";
import { CustomDropdownFilter } from "../search/dropdownFilters/CustomDropdownFilter";
import { PriceFromToDropdownFilter } from "../search/dropdownFilters/PriceFromToDropdownFilter";
import { FloorFromToDropdownFilter } from "../search/dropdownFilters/FloorsFromToDropdownFilter";
import { AreaFromToDropdownFilter } from "../search/dropdownFilters/AreaFromToDropdownFilter";
import { KitchenFromToDropdownFilter } from "../search/dropdownFilters/KitchenFromToDropdownFilter";
import { ButtonsGrid } from "../search/ButtonsGrid";
import axios from "axios";

export class CatalogueFilters {
    searchController;
    searchBar;
    searchBubbles;

    roomsFilter;
    yearsFilter;
    finishingFilter;
    toiletFilter;
    metroFilter;
    registrationFilter;
    mortgageFilter;
    apartmentFilter;

    yearsButtons;
    roomsButtons;
    metroButtons;
    finishingButtons;
    toiletButtons;
    registrationButtons;
    paymentButtons;
    apartmentButtons;

    pricesDropdown;
    floorsDropdown;
    areaDropdown;
    kitchenAreaDropdown;

    priceRange;
    floorsRange;
    areaRange;
    kitchenAreaRange;

    menuObject;
    openMenuButton;
    closeMenuButton;
    searchButton;

    offset = 0;
    limit = 18;
    isLoading = false;

    defaultMenuClass;

    searchRangeSliders = [];

    constructor() {
        this.searchController = new SearchController();
        this.searchBar = new SearchBar(this.searchController, "search-bar");
        this.searchBubbles = new SearchItemBubbles(this.searchController, true);
        this.limit = this.searchBar.searchBar.getAttribute("limit");

        this.roomsFilter = new CustomDropdownFilter(this.searchController, "filter-rooms-number", "names-dropdown item");
        this.yearsFilter = new CustomDropdownFilter(this.searchController, "filter-years-number", "names-dropdown item");
        this.toiletFilter = new CustomDropdownFilter(this.searchController, "filter-toilet", "names-dropdown item");
        this.finishingFilter = new CustomDropdownFilter(this.searchController, "filter-finishing", "names-dropdown item");
        this.metroFilter = new CustomDropdownFilter(this.searchController, "filter-metro-distance", "names-dropdown item");
        this.registrationFilter = new CustomDropdownFilter(this.searchController, "filter-registration", "names-dropdown item");
        this.mortgageFilter = new CustomDropdownFilter(this.searchController, "filter-mortgage", "names-dropdown item");
        this.apartmentFilter = new CustomDropdownFilter(this.searchController, "filter-apartments", "names-dropdown item");

        this.pricesDropdown = new PriceFromToDropdownFilter(this.searchController, "filter-price");
        this.floorsDropdown = new FloorFromToDropdownFilter(this.searchController, "filter-floors");
        this.areaDropdown = new AreaFromToDropdownFilter(this.searchController, "filter-area");
        this.kitchenAreaDropdown = new KitchenFromToDropdownFilter(this.searchController, "filter-kitchen-area");

        this.yearsButtons = new ButtonsGrid(this.searchController, "years-buttons", true);
        this.roomsButtons = new ButtonsGrid(this.searchController, "rooms-buttons", true);
        this.metroButtons = new ButtonsGrid(this.searchController, "metro-buttons", true);
        this.finishingButtons = new ButtonsGrid(this.searchController, "finishing-buttons", true);
        this.toiletButtons = new ButtonsGrid(this.searchController, "toilet-buttons", true);
        this.registrationButtons = new ButtonsGrid(this.searchController, "registration-buttons", true);
        this.paymentButtons = new ButtonsGrid(this.searchController, "payment-methods-buttons", true);
        this.apartmentButtons = new ButtonsGrid(this.searchController, "apartments-buttons", true);

        this.priceRange = new PriceRange("price-min-max-range", this.pricesDropdown);
        this.floorsRange = new SearchRange("floor-min-max-range", this.floorsDropdown);
        this.areaRange = new SearchRange("area-min-max-range", this.areaDropdown);
        this.kitchenAreaRange = new SearchRange("kitchen-area-min-max-range", this.kitchenAreaDropdown);

        this.searchRangeSliders = [this.priceRange, this.floorsRange, this.areaRange, this.kitchenAreaRange];

        this.loadMenu();

        this.searchController.loadParametersFromSearchBar();
        this.searchController.sendSearchParams = (request) => this.sendSearchRequest(request);

        document.addEventListener("searchItemAdded", () => this.searchController.submitSearch())
        document.addEventListener("searchItemsCleared", () => this.submitSearchWithClear());
    }

    loadMenu() {
        this.openMenuButton = document.getElementById("show-filters-menu-mobile");
        this.closeMenuButton = document.getElementById("close-filter-menu-button");
        this.menuObject = document.getElementsByClassName("search-catalogue base-container")[0];
        this.defaultMenuClass = this.menuObject.className;
        this.menuObject.addEventListener("submit", (event) => this.onSubmit(event));
        this.searchButton = this.menuObject.querySelector("input.common-button");

        const showButtonIds = ["show-filters-menu"];
        const allFilters = document.querySelector(".secondary");

        showButtonIds.forEach(id => document.getElementById(id).addEventListener("click", () => {
            if (allFilters.style.display != "grid") {
                allFilters.style.display = "grid";
            } else {
                allFilters.style.display = "none";
            }
        }));

        this.openMenuButton.addEventListener("click", () => this.menuObject.className = this.defaultMenuClass + " shown");
        this.closeMenuButton.addEventListener("click", () => this.menuObject.className = this.defaultMenuClass);
    }

    onSubmit(event) {
        event.preventDefault();
        this.submitSearchWithClear();
        this.menuObject.className = this.defaultMenuClass;
    }

    submitSearchWithClear() {
        this.offset = 0;
        this.searchController.submitSearch();
    }

    emitSearchStartEvent() {
        const event = new Event("searchStarted");
        document.dispatchEvent(event);
    }

    emitSearchEndEvent(response) {
        const event = new Event("searchEnded");
        event.response = response;
        document.dispatchEvent(event);
    }

    async sendSearchRequest(request) {
        if (this.isLoading) {
            return;
        }

        this.isLoading = true;
        const cardElement = this.searchBar.searchBar.getAttribute("cardelement");

        if (cardElement) {
            request.cardelement = cardElement;
        }

        request.limit = this.limit;
        request.offset = this.offset;
        this.searchButton.value = "Ищем квартиры...";

        this.emitSearchStartEvent();
        const response = await axios.get("/catalogue-items", { params: request });
        this.emitSearchEndEvent(response);

        if (response.data.fullfilledApartments > 0) {
            this.searchButton.value = "Найдено " + response.data.fullfilledApartments + " квартир";
        } else {
            this.searchButton.value = "Нет квартир";
        }

        //Сбрасываем ограничение на страницу если оно было переназначено, например из кнопки показать все
        this.limit = this.searchBar.searchBar.getAttribute("limit");
        this.isLoading = false;
    }

    resetSliders() {
        this.searchRangeSliders.forEach(rangeSlider => rangeSlider.resetSlider());
    }
}