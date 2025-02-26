import { addBuildingMarker, addBuildingPreview } from "./addBuildingMarker";
import { sleep } from "../sleep";

export async function fillCityMap(allItems, mapElement, yandexMap, ymaps3, delay = true) {
    const delayMs = 20;

    for (let i = 0; i < allItems.length; i++) {
        const buildingData = allItems[i];

        const marker = addBuildingMarker(yandexMap, buildingData, ymaps3);

        if (delay) {
            await sleep(delayMs);
        }

        addBuildingPreview(mapElement, marker, buildingData);

        if (delay) {
            await sleep(delayMs);
        }
    }
}