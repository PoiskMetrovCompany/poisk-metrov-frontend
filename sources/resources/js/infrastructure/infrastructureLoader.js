import { infraIconHealth, infraIconKindergarden, infraIconMetro, infraIconPark, infraIconSchool, infraIconShop, orange } from "../colors";

export async function loadInfrastructureMap() {
    await ymaps3.ready;
    const { YMap, YMapDefaultSchemeLayer, YMapDefaultFeaturesLayer, YMapMarker } = ymaps3;

    const map = new YMap(
        document.getElementById('map'),
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

    const locationData = infrastructure;

    const iconsForRequests = {
        "%D1%81%D1%82%D0%B0%D0%BD%D1%86%D0%B8%D0%B8+%D0%BC%D0%B5%D1%82%D1%80%D0%BE": "content-metro",
        "school": "content-book",
        "kindergarten": "content-children",
        "park": "content-tree",
        "shop": "content-shop",
        "sport": "content-dumbbell",
        "health": "content-hospital"
    };

    const colorsForIcons = {
        "content-metro": infraIconMetro,
        "content-book": infraIconSchool,
        "content-children": infraIconKindergarden,
        "content-tree": infraIconPark,
        "content-shop": infraIconShop,
        "content-dumbbell": infraIconShop,
        "content-hospital": infraIconHealth
    }


    addBuildingMarker(map, buildingCoordinates);
    if (locationData != null) {
        for (const [infrastructureType, infrastructureData] of Object.entries(locationData)) {
            infrastructureData.forEach(unit => {
                const coordinates = unit["geometry"]["coordinates"];
                addMapMarker(map, coordinates, iconsForRequests[infrastructureType]);
            });
        }
    }

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

    function addMapMarker(map, coordinates, icon) {
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
    }


    document.getElementsByClassName("ymaps3x0--map-copyrights_right")[0].style.right = "50px";
}