import { fillCityMap } from "./fillCityMap";
import { loadCityMap } from "./loadCityMap";
import { openMap } from "./openMap";

document.addEventListener("DOMContentLoaded", async () => {
    const [favoriteBuildingsMap, map, ymaps3] = await loadCityMap("favorite-buildings-map");
    const favoriteBuildingButtonIds = ["favorites-show-buildings-map-button"];

    if (ymaps3 === null || ymaps3 === undefined) {
        return;
    }

    favoriteBuildingButtonIds.forEach(id => {
        const showFavoriteBuildingsButton = document.getElementById(id);

        if (!showFavoriteBuildingsButton) {
            return;
        }

        showFavoriteBuildingsButton.onclick = () => openMap(favoriteBuildingsMap);
    });

    await fillCityMap(favoriteBuildings, favoriteBuildingsMap, map, ymaps3);
});
