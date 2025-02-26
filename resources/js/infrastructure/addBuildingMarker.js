import { orange } from "../colors";

export function addBuildingMarker(map, coordinates, previewLink, YMapMarker) {
    const markerElement = document.createElement('div');
    markerElement.className = "infrastructure menu icon-container icon d44x44";
    const markerIcon = document.createElement('img');

    if (previewLink.includes("http")) {
        markerIcon.src = previewLink.slice(1);
    } else {
        markerIcon.src = previewLink;
    }

    markerIcon.className = "icon orange d32x32";
    markerIcon.style.borderRadius = "50%";
    markerElement.appendChild(markerIcon);
    markerElement.style.borderColor = orange;
    markerElement.style.borderStyle = "solid";
    markerElement.style.borderWidth = "2px";

    const marker = new YMapMarker({ coordinates: coordinates, }, markerElement);

    map.addChild(marker);
}