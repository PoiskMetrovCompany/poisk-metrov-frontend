import { cityCenterCooridnates, getCity } from "../geolocation/getCity";
import { closeMap } from "./openMap";

export async function loadCityMap(mapId) {
    const mapElement = document.getElementById(mapId);

    if (!mapElement) {
        return [null, null, null];
    }

    const defaultClassName = mapElement.className;

    const coordinates = cityCenterCooridnates[getCity()];
    const zoom = 11;

    await ymaps3.ready;

    if (ymaps3 === undefined) {
        return;
    }

    const { YMap, YMapDefaultSchemeLayer, YMapDefaultFeaturesLayer, YMapListener } = ymaps3;
    const listener = new YMapListener({});

    const map = new YMap(
        mapElement,
        {
            location:
            {
                center: coordinates,
                zoom: zoom
            }
        }
    );

    map.addChild(new YMapDefaultSchemeLayer());
    map.addChild(new YMapDefaultFeaturesLayer());
    map.addChild(listener);

    mapElement.querySelector(".full-screen-map.close")?.addEventListener("click", () => closeMap(mapElement, defaultClassName));

    document.getElementsByClassName("ymaps3x0--map-copyrights_right")[0].style.right = "50px";

    return [mapElement, map, ymaps3];
}