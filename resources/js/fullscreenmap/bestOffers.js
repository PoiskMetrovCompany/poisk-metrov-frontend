import { fillCityMap } from "./fillCityMap";
import { loadCityMap } from "./loadCityMap";
import { openMap } from "./openMap";

document.addEventListener("DOMContentLoaded", async () => {
    const [bestOffersMap, map, ymaps3] = await loadCityMap("best-offers-map");
    const bestOfferButtonIds = ["show-best-offers-on-map", "show-best-offers-on-map-mobile"];

    if (ymaps3 === null || ymaps3 === undefined) {
        return;
    }

    bestOfferButtonIds.forEach(id => {
        const showBestOffersButton = document.getElementById(id);

        if (!showBestOffersButton) {
            return;
        }

        showBestOffersButton.onclick = () => openMap(bestOffersMap);
    });

    await fillCityMap(allItemsInCity, bestOffersMap, map, ymaps3);
});
