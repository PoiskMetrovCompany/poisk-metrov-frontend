export class NothingFound {
    searchStartEvent;
    searchEndEvent;

    constructor(searchStartEvent, searchEndEvent) {
        this.searchStartEvent = searchStartEvent;
        this.searchEndEvent = searchEndEvent;

        document.addEventListener(this.searchStartEvent, () => this.onSearchStart());
        document.addEventListener(this.searchEndEvent, (event) => this.onSearchEnd(event));
    }

    onSearchStart() {
        const nothingFound = document.getElementById("nothing-found");
        nothingFound.style.display = "none";
    }

    onSearchEnd(event) {
        const nothingFound = document.getElementById("nothing-found");
        nothingFound.style.display = (event.response.data.catalogueItems.length > 0 ? "none" : "block");
    }
}