export class LoadingDots {
    searchStartEvent;
    searchEndEvent;
    dotsId;

    constructor(searchStartEvent, searchEndEvent, dotsId) {
        this.searchStartEvent = searchStartEvent;
        this.searchEndEvent = searchEndEvent;
        this.dotsId = dotsId;

        document.addEventListener(this.searchStartEvent, () => this.onSearchStart());
        document.addEventListener(this.searchEndEvent, (event) => this.onSearchEnd(event));
    }

    onSearchStart() {
        const loader = document.getElementById(this.dotsId);
        loader.style.display = "flex";
    }

    onSearchEnd(event) {
        const loader = document.getElementById(this.dotsId);
        loader.style.display = "none";
    }
}