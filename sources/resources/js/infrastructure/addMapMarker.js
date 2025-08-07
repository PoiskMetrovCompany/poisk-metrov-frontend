import { colorsForIcons } from "./colorsForIcons";
import { orange } from "../colors";

export function addMapMarker(map, coordinates, icon, YMapMarker) {
    const markerElement = document.createElement('div');
    markerElement.className = "infrastructure menu icon-container icon d44x44";
    markerElement.style.backgroundColor = orange;
    const markerIcon = document.createElement('div');
    markerIcon.className = "icon " + icon + " d24x24";
    markerElement.appendChild(markerIcon);
    markerElement.style.backgroundColor = colorsForIcons[icon];
    markerElement.style.borderColor = markerElement.style.backgroundColor;
    markerElement.style.borderStyle = "solid";
    markerElement.style.borderWidth = "2px";
    markerElement.style.display = "none";

    const marker = new YMapMarker({ coordinates: coordinates, }, markerElement);

    map.addChild(marker);

    return {
        marker: marker,
        markerElement: markerElement
    }
}