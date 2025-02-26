export class FoundCount {
    searchStartEvent;
    searchEndEvent;

    constructor(searchStartEvent, searchEndEvent) {
        this.searchStartEvent = searchStartEvent;
        this.searchEndEvent = searchEndEvent;

        document.addEventListener(this.searchStartEvent, () => this.onSearchStart());
        document.addEventListener(this.searchEndEvent, (event) => this.onSearchEnd(event));
    }

    onSearchStart() {
        const foundCount = document.getElementById("found-count");
        foundCount.textContent = "Ищем...";
    }

    onSearchEnd(event) {
        const foundCount = document.getElementById("found-count");
        foundCount.style.display = (event.response.data.catalogueItems.length > 0 ? "block" : "none");
        const buildingCount = event.response.data.buildingCount;
        const fullfilledCount = event.response.data.fullfilledCount;

        foundCount.textContent = `Найдено ${fullfilledCount} ЖК из ${buildingCount}`;
    }
}