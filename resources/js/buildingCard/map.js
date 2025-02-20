export async function loadMap(mapContainerId = "temp-map-container") {
    const coordinates = [0, 0];
    const zoom = 17;

    const mapContainer = document.getElementById(mapContainerId);

    await ymaps3.ready;

    if (ymaps3 === undefined) {
        return [null, null, null];
    }

    const { YMap, YMapDefaultSchemeLayer, YMapDefaultFeaturesLayer, YMapListener } = ymaps3;
    const listener = new YMapListener({});

    const map = new YMap(
        mapContainer,
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

    document.getElementsByClassName("ymaps3x0--map-copyrights_right")[0].style.right = "50px";

    const mapElement = mapContainer.getElementsByTagName("ymaps")[0];

    return [map, ymaps3, mapElement];
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
    const zoom = 17;
    const { YMapMarker } = ymaps3;

    let marker;

    mapElement.style.display = "none";
    mapButton.onclick = () => {
        if (mapElement.style.display == "none") {
            deselectAll();
            select();
        } else {
            deselect();
        }
    }

    //Очень важно! Ресайз карты происходит при изменении размеров не карты, не родителя, а стартового элемента где создалась карта
    function adjustMapSize() {
        //Чтобы анимация уменьшения не ломала высоту
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

    function select() {
        mapElement.style.display = "block";
        mapButton.className = mapButton.className.replace("card-button", "card-button orange");

        map.update({
            location:
            {
                center: coordinates,
                zoom: zoom
            }
        })
        marker = addBuildingMarker(map, coordinates);

        mapContainer.appendChild(yMapElement);
        adjustMapSize();
    }

    function deselect() {
        mapElement.style.display = "none";
        mapButton.className = mapButton.className.replace("card-button orange", "card-button");
        if (marker) {
            map.removeChild(marker);
        }
        tempMapContainer.appendChild(yMapElement);
    }

    const deselector = () => deselect();

    function addBuildingMarker(map, coordinates) {
        const markerElement = document.createElement('div');
        markerElement.className = "infrastructure menu icon-container icon d44x44";
        const markerIcon = document.createElement('img');
        markerIcon.src = previewImage.style.backgroundImage.substring(5, previewImage.style.backgroundImage.length - 2);
        markerIcon.className = "icon orange d32x32";
        markerIcon.style.borderRadius = "50%";
        markerElement.appendChild(markerIcon);
        markerElement.style.borderColor = "#EC7D3F";
        markerElement.style.borderStyle = "solid";
        markerElement.style.borderWidth = "2px";

        const marker = new YMapMarker({ coordinates: coordinates, }, markerElement);
        map.addChild(marker);
        return marker;
    }

    return deselector;
}