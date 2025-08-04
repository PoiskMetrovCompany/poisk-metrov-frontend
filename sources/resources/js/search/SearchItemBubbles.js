import { SearchController } from "./SearchController";

export class SearchItemBubbles {
    bubblesContainer = null;
    clearAllButton = null;
    searchController = null;

    bubbles = [];

    searchOnRemove = false;

    constructor(searchController, searchOnRemove = false) {
        this.searchController = searchController;
        this.bubblesContainer = document.getElementsByClassName("filter-bubble base-container")[0];
        this.clearAllButton = this.bubblesContainer.children[0];
        this.clearAllButton.addEventListener("click", () => this.clearAll());
        this.searchOnRemove = searchOnRemove;

        document.addEventListener("searchItemAdded", (event) => this.addBubble(event));
        document.addEventListener("searchItemRemoved", (event) => this.removeBubble(event.searchid));
    }

    addBubble(event) {
        let newBubble = null;

        if (!event.nobubble) {
            newBubble = document.createElement("div");
            newBubble.className = "filter-bubble container";

            const textElement = document.createElement("span");
            textElement.textContent = event.displayName;
            newBubble.appendChild(textElement);

            const cross = document.createElement("div");
            cross.className = "icon action-close-2";
            newBubble.appendChild(cross);

            newBubble.addEventListener("click", () => {
                this.searchController.removeItemWithId(event.searchid);

                if (this.searchOnRemove) {
                    this.searchController.submitSearch();
                }
            });
            newBubble.searchid = event.searchid;

            this.bubblesContainer.appendChild(newBubble);
            this.bubbles.push(newBubble);
        }

        this.updateClearButton();
    }

    removeBubble(searchid) {
        const bubble = this.bubbles.filter(bubble => bubble.searchid == searchid)[0];

        if (bubble) {
            bubble.remove();
            this.bubbles = this.bubbles.filter(bubble => bubble.searchid != searchid);
        }

        this.updateClearButton();
    }

    updateClearButton() {
        if (this.bubbles.length == 0) {
            this.clearAllButton.style.display = "none";
        } else {
            this.clearAllButton.style.display = "flex";
        }
    }

    clearAll() {
        this.searchController.clearAll();
    }
}