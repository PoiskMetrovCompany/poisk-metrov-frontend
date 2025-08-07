document.addEventListener("DOMContentLoaded", async () => {
    await loadPresentationMap();
});

let isMapLoaded = false;

async function loadPresentationMap() {
    const mapElements = document.querySelectorAll('.map');
    await ymaps3.ready;

    mapElements.forEach(mapElement => {
        let previewLink = mapElement.getAttribute("previewlink");
        const infraString = mapElement.getAttribute("infrastructure");
        let infrastructure = {};

        if (infraString != '') {
            infrastructure = JSON.parse(infraString);
        }
        const longitude = Number.parseFloat(mapElement.getAttribute("longitude"));
        const latitude = Number.parseFloat(mapElement.getAttribute("latitude"));
        const zoom = Number.parseFloat(mapElement.getAttribute("zoom"));
        const buildingCoordinates = [longitude, latitude];

        const { YMap, YMapDefaultSchemeLayer, YMapDefaultFeaturesLayer, YMapMarker } = ymaps3;

        const map = new YMap(
            mapElement,
            {
                location:
                {
                    center: buildingCoordinates,
                    zoom: zoom
                }
            }
        );

        map.addChild(new YMapDefaultSchemeLayer());
        map.addChild(new YMapDefaultFeaturesLayer());
        //TODO: fix infrastructure retrieval and add it here

        if (previewLink && previewLink != 'data:image/png;base64,') {
            addBuildingMarkerForPresentation(map, buildingCoordinates, previewLink, YMapMarker);
        }
    });

    isMapLoaded = true;
}

function addBuildingMarkerForPresentation(map, coordinates, previewLink, YMapMarker) {
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
    markerElement.style.borderColor = "#EC7D3F";
    markerElement.style.borderStyle = "solid";
    markerElement.style.borderWidth = "2px";

    const marker = new YMapMarker({ coordinates: coordinates, }, markerElement);

    map.addChild(marker);
}