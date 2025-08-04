import { sharePageMenu } from "../pageShare/sharePageMenu";
import { sleep } from "../sleep";
import { loadLikeButton } from "./like";
import { loadMap, setMap } from "./map";
import { slideAnimation } from "./slideAnimation";
import { spriteCardGallery } from "./spriteCardGallery";

let map;
let ymaps3;
let mapElement;
let isMapLoaded = false;
let deselectors = [];

export async function loadBuildingCards(defaultClassName = "building-cards container", wait = false) {
    const buildings = Array.from(document.getElementsByClassName(defaultClassName));

    if (!isMapLoaded) {
        [map, ymaps3, mapElement] = await loadMap();

        isMapLoaded = true;
    }

    for (let i = 0; i < buildings.length; i++) {
        const buildingCard = buildings[i];

        slideAnimation(buildingCard);
        spriteCardGallery(buildingCard);
        const id = buildingCard.getAttribute('id');
        sharePageMenu('share-real-estate-button-' + id, () => window.location.host + "/" + id);
        loadLikeButton(buildingCard);

        if (ymaps3) {
            if (wait) {
                await sleep(10);
            }

            const deselector = await setMap(buildingCard, map, ymaps3, mapElement, () => deselectors.forEach(deselector => deselector()));
            deselectors.push(deselector);
        }
    }
}