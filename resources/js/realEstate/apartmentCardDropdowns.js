import { Paginator } from "../paginators/paginator";
import { TabsGrid } from "../TabsGrid";
import { loadPlanCardButtons } from "../planCard/loadPlanCardButtons";

export function loadDropdowns(priceSortingForRoomCount, areaSortingForRoomCount) {
    const dropdownHeaders = Array.from(document.getElementsByClassName("plans-filter apartment-dropdown header"));
    const dropdowns = Array.from(document.getElementsByClassName("plans-filter apartment-dropdown container"));

    dropdownHeaders.forEach((header, i) => {
        const dropdown = dropdowns[i];
        const defaultClass = dropdown.className;
        const tick = header.getElementsByClassName("icon arrow-tailless orange")[0];

        const buttonsContainer = document.getElementById("apartment-switch-buttons-" + i);

        let priceSortingTabs = new TabsGrid("apartment-dropdown-price-sort-" + i, (tab) => {
            const event = new Event("priceSortingChanged");
            event.text = tab.textContent;
            event.apartmentType = tab.getAttribute("apartmentType");
            event.roomCount = tab.getAttribute("roomCount");
            event.apartmentGroupNumber = i;

            document.dispatchEvent(event);
        });

        priceSortingTabs.enableClass = "active";
        priceSortingTabs.disableClass = "";
        priceSortingTabs.buttonClass = "plans-filter apartment-dropdown button";
        priceSortingTabs.setFirstAsCurrent();

        let areaSortingTabs = new TabsGrid("apartment-dropdown-area-sort-" + i, (tab) => {
            const event = new Event("areaSortingChanged");
            event.text = tab.textContent;
            event.apartmentType = tab.getAttribute("apartmentType");
            event.roomCount = tab.getAttribute("roomCount");
            event.apartmentGroupNumber = i;

            document.dispatchEvent(event);
        });

        areaSortingTabs.enableClass = priceSortingTabs.enableClass;
        areaSortingTabs.disableClass = priceSortingTabs.disableClass;
        areaSortingTabs.buttonClass = priceSortingTabs.buttonClass;
        areaSortingTabs.setFirstAsCurrent();

        if (buttonsContainer) {
            const paginator = new Paginator();
            paginator.pageClass = "plans-filter apartment-dropdown card-grid";
            paginator.buttonsGridId = buttonsContainer.id;
            paginator.pagesContainerId = "apartment-dropdown-" + i;
            paginator.load();

            //If pagination is disabled, show all pages
            const realButtonsGrid = paginator.buttons[0].parentElement;

            if (window.getComputedStyle(realButtonsGrid).display == "none") {
                paginator.pages.forEach(page => page.style.display = "grid");
            }
        }

        header.onclick = () => {
            const isOpen = dropdown.classList.contains("open");

            if (isOpen) {
                dropdown.classList.remove("animate__slideInDown");
                // dropdown.classList.add("animate__fadeInUp");
                dropdown.classList.add("animate__slideInUp");

                dropdown.addEventListener('animationend', handleAnimationEnd);

                function handleAnimationEnd() {
                    // dropdown.classList.remove("open", "animate__fadeInUp");
                    dropdown.classList.remove("open", "animate__slideInUp");
                    tick.style.transform = "rotateX(180deg)";

                    dropdown.removeEventListener('animationend', handleAnimationEnd);
                }
            } else {
                // dropdown.classList.remove("animate__fadeInUp");
                dropdown.classList.remove("animate__slideInUp");
                dropdown.classList.add("animate__slideInDown");
                dropdown.classList.add("open");
                tick.style.transform = "rotateX(0deg)";
            }
        };
    });

    loadPlanCardButtons();
}
