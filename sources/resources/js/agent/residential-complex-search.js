import { getDataFromOption } from "../search/getDataFromOption";

document.addEventListener("DOMContentLoaded", () => {
    const buttongrid = document.getElementById("agent-catalogue-recommendation-buttons");
    const buttons = Array.from(buttongrid.children);

    buttons.forEach((button, i) => {
        if (button.getAttribute("more") != null) {
            button.addEventListener("click", () => {
                buttongrid.setAttribute("showall", 1);
            });
        } else if (i > buttons.length - 2) {
            button.addEventListener("click", () => {
                buttongrid.setAttribute("showall", 0);
            });
        } else {
            button.addEventListener("click", () => {
                const options = getDataFromOption(button);
                document.searchController.addOrRemoveItemWithId(options);
            });
        }
    });
});