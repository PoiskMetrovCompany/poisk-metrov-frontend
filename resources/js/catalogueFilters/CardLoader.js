export class CardLoader {
    buildingCardsGrid;
    searchStartEvent;
    searchEndEvent;
    registeredCardElements = [
        "building-card",
        "agent-building-card",
        "wide-building-card"
    ]

    constructor(searchStartEvent, searchEndEvent) {
        this.buildingCardsGrid = document.getElementById("building-cards-grid");
        this.searchStartEvent = searchStartEvent;
        this.searchEndEvent = searchEndEvent;

        document.addEventListener(this.searchStartEvent, () => this.onSearchStart());
        document.addEventListener(this.searchEndEvent, (event) => this.onSearchEnd(event));
    }

    onSearchStart() {
        this.transparentCards();
    }

    async onSearchEnd(event) {
        this.clearCards();

        this.buildingCardsGrid.innerHTML += event.response.data.catalogueItems.join('');
    }

    transparentCards() {
        const cards = Array.from(this.buildingCardsGrid.querySelectorAll(this.registeredCardElements));

        for (let i = 0; i < cards.length; i++) {
            cards[i].style.opacity = 0.5;
            cards[i].style.pointerEvents = "none";
        }
    }

    clearCards() {
        const cards = Array.from(this.buildingCardsGrid.querySelectorAll(this.registeredCardElements));

        for (let i = 0; i < cards.length; i++) {
            cards[i].remove();
        }
    }
}