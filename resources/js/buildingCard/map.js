let globalMap = null;

export async function loadMap(mapContainerId = "temp-map-container") {
    const coordinates = [localStorage.getItem('longitude'), localStorage.getItem('latitude')];
    const zoom = 17;

    const mapContainer = document.getElementById(mapContainerId);

    await ymaps3.ready;

    if (ymaps3 === undefined) {
        return [null, null, null];
    }

    const { YMap, YMapDefaultSchemeLayer, YMapDefaultFeaturesLayer, YMapListener } = ymaps3;
    const listener = new YMapListener({});

    if (globalMap) {
        globalMap.destroy();
        globalMap = null;
    }

    globalMap = new YMap(
        mapContainer,
        {
            location: {
                center: coordinates,
                zoom: zoom
            }
        }
    );

    globalMap.addChild(new YMapDefaultSchemeLayer());
    globalMap.addChild(new YMapDefaultFeaturesLayer());
    globalMap.addChild(listener);

    document.getElementsByClassName("ymaps3x0--map-copyrights_right")[0].style.right = "50px";

    const mapElement = mapContainer.getElementsByTagName("ymaps")[0];

    return [globalMap, ymaps3, mapElement];
}

export async function updateMap() {
    try {
        const longitude = localStorage.getItem('longitude');
        const latitude = localStorage.getItem('latitude');

        const coordinates = [longitude, latitude];
        const zoom = 17;

        if (!globalMap) {
            await loadMap();
            return;
        }

        const mapContainer = document.getElementById("temp-map-container");
        if (mapContainer) {
            const existingMarkers = mapContainer.querySelectorAll('.infrastructure.menu.icon-container');
            existingMarkers.forEach(marker => marker.remove());
        }

        await globalMap.update({
            location: {
                center: coordinates,
                zoom: zoom
            }
        });

        const { YMapMarker } = ymaps3;
        const markerElement = document.createElement('div');
        markerElement.className = "infrastructure menu icon-container icon d44x44";

        const marker = new YMapMarker({ coordinates: coordinates }, markerElement);
        globalMap.addChild(marker);

    } catch (error) {
        try {
            await loadMap();
        } catch (e) {
            console.error('Error recreating map:', e);
        }
    }
}

export async function setMap(cardElement, map, ymaps3, yMapElement, deselectAll) {
    const tempMapContainer = document.getElementById("temp-map-container");
    const mapButton = document.getElementById(cardElement.id + "-map-button");
    const previewImage = document.getElementById("background-" + cardElement.id);
    const mapContainer = document.getElementById(cardElement.id + "-map");
    const mapElement = document.getElementById(cardElement.id + "-map");
    const longitude = Number.parseFloat(cardElement.getAttribute("long"));
    const latitude = Number.parseFloat(cardElement.getAttribute("lat"));
    const coordinates = [longitude, latitude];

    let marker;
    let currentMap = map;

    mapElement.style.display = "none";
    mapButton.onclick = async () => {
        if (mapElement.style.display == "none") {
            deselectAll();
            await select();
        } else {
            deselect();
        }
    }

    function adjustMapSize() {
        if (mapContainer.getElementsByTagName("ymaps").length > 0) {
            mapContainer.style.width = mapElement.parentElement.clientWidth + 4 + "px";
            mapContainer.style.height = mapElement.parentElement.clientHeight + 4 + "px";
            tempMapContainer.style.width = mapElement.clientWidth + 4 + "px";
            tempMapContainer.style.height = mapElement.clientHeight + 4 + "px";
        }
    }

    const resizer = new ResizeObserver(adjustMapSize);
    resizer.observe(mapElement.parentElement);
    resizer.observe(document.body);

    async function select() {
        try {
            mapElement.style.display = "block";
            mapButton.className = mapButton.className.replace("card-button", "card-button orange");

            localStorage.setItem('longitude', longitude);
            localStorage.setItem('latitude', latitude);

            await updateMap();

            if (globalMap) {
                currentMap = globalMap;
                mapContainer.appendChild(tempMapContainer.getElementsByTagName("ymaps")[0]);
                adjustMapSize();
            }
        } catch (error) {
            console.error('Error in select:', error);
        }
    }

    function deselect() {
        mapElement.style.display = "none";
        mapButton.className = mapButton.className.replace("card-button orange", "card-button");

        if (yMapElement && tempMapContainer) {
            tempMapContainer.appendChild(yMapElement);
        }
    }

    return deselect;
}
