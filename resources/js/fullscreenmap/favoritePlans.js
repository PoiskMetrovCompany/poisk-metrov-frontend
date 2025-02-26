import { fillCityMap } from "./fillCityMap";
import { loadCityMap } from "./loadCityMap";
import { openMap } from "./openMap";

document.addEventListener("DOMContentLoaded", async () => {
    const [favoritePlansMap, map, ymaps3] = await loadCityMap("favorite-plans-map");
    const favoritePlanButtonIds = ["favorites-show-plans-map-button"];

    if (ymaps3 === null || ymaps3 === undefined) {
        return;
    }

    favoritePlanButtonIds.forEach(id => {
        const showFavoritePlansButton = document.getElementById(id);

        if (!showFavoritePlansButton) {
            return;
        }

        showFavoritePlansButton.onclick = () => openMap(favoritePlansMap);
    });

    await fillCityMap(buildingsFromFavoritePlans, favoritePlansMap, map, ymaps3);
});
