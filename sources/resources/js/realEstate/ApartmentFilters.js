import { PriceRange } from "../search/PriceRange";
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
import { loadDropdowns } from "./apartmentCardDropdowns";

export class ApartmentFilters {
    searchController;
    searchBubbles;

    roomsFilter;
    yearsFilter;
    finishingFilter;
    toiletFilter;
    metroFilter;
    registrationFilter;
    mortgageFilter;
    apartmentFilter;
    corpusFilter;

    yearsButtons;
    roomsButtons;
    metroButtons;
    finishingButtons;
    toiletButtons;
    registrationButtons;
    paymentButtons;
    apartmentButtons;
    corpusButtons;

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

    offset = 0;
    limit = 18;
    isLoading = false;

    defaultMenuClass;

    searchRangeSliders = [];

    apartmentDropdownsContainer;
    nothingFound;

    priceSortingForRoomCount = {};
    areaSortingForRoomCount = {};

    constructor() {
        this.searchController = new SearchController();
        this.searchBubbles = new SearchItemBubbles(this.searchController, true);

        this.yearsFilter = new CustomDropdownFilter(this.searchController, "filter-years-number", "names-dropdown item");
        this.toiletFilter = new CustomDropdownFilter(this.searchController, "filter-toilet", "names-dropdown item");
        this.finishingFilter = new CustomDropdownFilter(this.searchController, "filter-finishing", "names-dropdown item");
        this.mortgageFilter = new CustomDropdownFilter(this.searchController, "filter-mortgage", "names-dropdown item");
        this.apartmentFilter = new CustomDropdownFilter(this.searchController, "filter-apartments", "names-dropdown item");
        this.corpusFilter = new CustomDropdownFilter(this.searchController, "filter-corpus", "names-dropdown item");

        this.pricesDropdown = new PriceFromToDropdownFilter(this.searchController, "filter-price");
        this.floorsDropdown = new FloorFromToDropdownFilter(this.searchController, "filter-floors");
        this.areaDropdown = new AreaFromToDropdownFilter(this.searchController, "filter-area");
        this.kitchenAreaDropdown = new KitchenFromToDropdownFilter(this.searchController, "filter-kitchen-area");

        this.yearsButtons = new ButtonsGrid(this.searchController, "years-buttons", true);
        this.corpusButtons = new ButtonsGrid(this.searchController, "corpus-buttons", true);
        this.finishingButtons = new ButtonsGrid(this.searchController, "finishing-buttons", true);
        this.toiletButtons = new ButtonsGrid(this.searchController, "toilet-buttons", true);
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
        document.addEventListener("priceSortingChanged", (event) => this.onPriceSortingChanged(event));
        document.addEventListener("areaSortingChanged", (event) => this.onAreaSortingChanged(event));

        loadDropdowns(this.priceSortingForRoomCount, this.areaSortingForRoomCount);
    }

    loadMenu() {
        this.openMenuButton = document.getElementById("show-filters-menu-mobile");
        this.closeMenuButton = document.getElementById("close-filter-menu-button");
        this.menuObject = document.getElementsByClassName("plans-filter filters-container")[0];
        this.defaultMenuClass = this.menuObject.className;
        this.menuObject.addEventListener("submit", (event) => this.onSubmit(event));

        this.openMenuButton.addEventListener("click", () => {
            if (this.menuObject.className.endsWith("open")) {
                this.menuObject.className = this.defaultMenuClass;
            } else {
                this.menuObject.className = this.defaultMenuClass + " open";
            }
        });
    }

    onPriceSortingChanged(event) {
        let priceSortOrder = "ASC";

        if (event.text = "Сначала дороже") {
            priceSortOrder = "DESC";
        }

        if (!this.priceSortingForRoomCount[event.apartmentType]) {
            this.priceSortingForRoomCount[event.apartmentType] = [];
        }

        this.priceSortingForRoomCount[event.apartmentType][event.roomCount] = priceSortOrder;

        this.searchController.submitSearch();
    }

    onAreaSortingChanged(event) {
        let areaSortOrder = "ASC";

        if (event.text = "По росту площади") {
            areaSortOrder = "DESC";
        }

        if (!this.areaSortingForRoomCount[event.apartmentType]) {
            this.areaSortingForRoomCount[event.apartmentType] = [];
        }

        this.areaSortingForRoomCount[event.apartmentType][event.roomCount] = areaSortOrder;

        this.searchController.submitSearch();
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

        this.apartmentDropdownsContainer = document.getElementById("apartment-dropdowns-container");
        this.nothingFound = document.getElementById("nothing-found");

        const buildingCode = window.location.href.substring(window.location.href.lastIndexOf('/') + 1).split('?')[0];

        request.priceSortOrder = Array.from(this.priceSortingForRoomCount);
        request.areaSortOrder = Array.from(this.areaSortingForRoomCount);

        this.apartmentDropdownsContainer.style.opacity = 0.5;
        this.emitSearchStartEvent();
        const response = await axios.get(`/apartments-list/${buildingCode}`, { params: request });
        this.emitSearchEndEvent(response);
        this.nothingFound.style.display = (response.data == "" ? "block" : "none");
        this.apartmentDropdownsContainer.style.opacity = 1;
        this.apartmentDropdownsContainer.innerHTML = response.data;

        loadDropdowns(this.priceSortingForRoomCount, this.areaSortingForRoomCount);

        this.isLoading = false;
    }

    resetSliders() {
        this.searchRangeSliders.forEach(rangeSlider => rangeSlider.resetSlider());
    }
}