import { orange } from "../colors";

export async function loadOfficesMap(officeLocations, mapId) {
    let zoom = 13;
    let previewLink = "/placeholders/placeholder-13.png";

    await ymaps3.ready;
    const { YMap,  YMapDefaultSchemeLayer, YMapDefaultFeaturesLayer, YMapMarker, YMapFeature, YMapControls} = ymaps3;
    
    let map = new YMap(
        document.getElementById(mapId),
        {
            location: {
                center: officeLocations[0],
                zoom: zoom
            }
        }
    );

    map.addChild(new YMapDefaultSchemeLayer());
    map.addChild(new YMapDefaultFeaturesLayer());
    officeLocations.forEach(office => {
        addBuildingMarker(map, office);
    });

    function addBuildingMarker(map, coordinates) {
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

    document.getElementsByClassName("ymaps3x0--map-copyrights_right")[0].style.right = "50px";
}
