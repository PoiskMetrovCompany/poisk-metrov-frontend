import { getDataFromOption } from "./getDataFromOption";

export class ButtonsGrid {
    lastSelectedData;
    buttons = [];
    isUnique = false;

    constructor(searchController, gridId, searchOnRemove = false, onClick = undefined) {
        const grid = document.getElementById(gridId);
        this.buttons = Array.from(grid.getElementsByClassName("filter-toggles button"));
        this.isUnique = grid.getAttribute("allowMultiple") != '1';

        this.buttons.forEach((button, i) => {
            const defaultClassname = button.className;
            const addObject = getDataFromOption(button);

            document.addEventListener("searchItemAdded", (event) => {
                if (event.searchid == addObject.searchid) {
                    button.className = defaultClassname + " selected";

                    if (this.isUnique && this.lastSelectedData && this.lastSelectedData != event.searchid) {
                        searchController.removeItemWithId(this.lastSelectedData);
                    }

                    this.lastSelectedData = event.searchid;
                }
            });

            document.addEventListener("searchItemRemoved", (event) => {
                if (event.searchid == addObject.searchid) {
                    button.className = defaultClassname;
                }
            });

            if (onClick == undefined) {
                button.addEventListener("click", () => {
                    searchController.addOrRemoveItemWithId(addObject);

                    if (searchOnRemove) {
                        searchController.submitSearch();
                    }
                });
            } else {
                button.addEventListener("click", () => onClick(i));
            }
        });
    }
}