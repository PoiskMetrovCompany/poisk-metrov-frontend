import { SearchController } from "../search/SearchController";
import { SearchItemBubbles } from "../search/SearchItemBubbles";
import { PriceRange } from "../search/PriceRange";
import { SearchBar } from "../search/nameSearch/SearchBar";
import { ButtonsGrid } from "../search/ButtonsGrid";
import { CustomDropdownFilter } from "../search/dropdownFilters/CustomDropdownFilter";
import { PriceFromToDropdownFilter } from "../search/dropdownFilters/PriceFromToDropdownFilter";

document.addEventListener("DOMContentLoaded", () => {
    const searchController = new SearchController();
    const searchBar = new SearchBar(searchController);
    const searchBubbles = new SearchItemBubbles(searchController);

    const dateFilter = new CustomDropdownFilter(searchController, "filter-completion-date");
    const roomsFilter = new CustomDropdownFilter(searchController, "filter-rooms-number");
    const pricesFilter = new PriceFromToDropdownFilter(searchController, "filter-price");

    dateFilter.sendRequestOnChange = false;
    roomsFilter.sendRequestOnChange = false;
    pricesFilter.sendRequestOnChange = false;

    const yearsButtons = new ButtonsGrid(searchController, "years-buttons");
    const roomsButtons = new ButtonsGrid(searchController, "rooms-buttons");

    const priceRange = new PriceRange('price-min-max-range', pricesFilter);

    searchBubbles.clearAllButton.addEventListener("click", () => priceRange.resetSlider());

    const homePageSearchForm = document.getElementsByClassName("search-grid base-container")[0];

    homePageSearchForm.addEventListener("submit", (event) => {
        event.preventDefault();
        searchController.submitSearch();
    });

    loadOpenCloseButtons();
});

function loadOpenCloseButtons() {
    const showButtonIds = ["show-filters-menu-mobile"];
    const menu = document.querySelector(".search-grid.base-container");
    const defaultMenuClass = menu.className;
    const parentElement = menu.parentElement;

    showButtonIds.forEach(id => document.getElementById(id).addEventListener("click", () => {
        parentElement.style.display = "block";
        menu.className += " open-window"
    }));

    document.getElementById("close-filter-menu-button").addEventListener("click", () => {
        parentElement.style.display = "";
        menu.className = defaultMenuClass
    });
}