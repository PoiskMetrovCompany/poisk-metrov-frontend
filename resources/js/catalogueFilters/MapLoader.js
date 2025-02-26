import { fillCityMap } from "../fullscreenmap/fillCityMap";
import { loadCityMap } from "../fullscreenmap/loadCityMap";
import { openMap } from "../fullscreenmap/openMap";
import { sleep } from "../sleep";

export class MapLoader {
    searchStartEvent;
    searchEndEvent;
    catalogueMap;
    map;
    ymaps3;
    mapContainer;
    fillingInProgress = false;

    constructor(searchStartEvent, searchEndEvent) {
        this.searchStartEvent = searchStartEvent;
        this.searchEndEvent = searchEndEvent;

        document.addEventListener(this.searchStartEvent, () => this.onSearchStart());
        document.addEventListener(this.searchEndEvent, (event) => this.onSearchEnd(event));

        this.loadMap();
    }

    async loadMap() {
        const [catalogueMap, map, ymaps3] = await loadCityMap("catalogue-map");
        const catalogueButtonIds = ["catalogue-map-button", "catalogue-map-button-mobile", "agent-catalogue-map-button"];

        if (ymaps3 === null || ymaps3 === undefined) {
            return;
        }

        this.catalogueMap = catalogueMap;
        this.map = map;
        this.ymaps3 = ymaps3;
        this.mapContainer = catalogueMap.parentElement;

        catalogueButtonIds.forEach(id => {
            const showCatalogueButton = document.getElementById(id);

            if (!showCatalogueButton) {
                return;
            }

            showCatalogueButton.onclick = () => openMap(catalogueMap);
        });

        this.fillingInProgress = true;
        await fillCityMap(catalogueBuildings, catalogueMap, map, ymaps3);
        this.fillingInProgress = false;
    }

    onSearchStart() {
        this.clearMapContent();
    }

    clearMapContent() {
        const mapCards = Array.from(document.getElementsByClassName("full-screen-map building-card"));

        for (let i = 0; i < mapCards.length; i++) {
            mapCards[i].remove();
        }

        const markers = Array.from(document.getElementsByClassName("full-screen-map location-with-name"));

        for (let i = 0; i < markers.length; i++) {
            markers[i].remove();
        }
    }

    async onSearchEnd(event) {
        while (this.fillingInProgress) {
            await sleep(20);
        }

        this.fillingInProgress = true;
        await fillCityMap(event.response.data.catalogueBuildings, this.catalogueMap, this.map, this.ymaps3, false);
        this.fillingInProgress = false;
    }
}