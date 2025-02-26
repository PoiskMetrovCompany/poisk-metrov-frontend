let globalMap;

export function addBuildingMarker(map, buildingData, ymaps3) {
    const markerElement = document.createElement("div");
    markerElement.className = "full-screen-map location-with-name";
    const markerText = document.createElement("span");
    markerText.textContent = buildingData.name;
    const markerIcon = document.createElement("div");
    markerIcon.className = "icon place d20x20 white";
    const markerIconContainer = document.createElement("div");
    markerIconContainer.className = "full-screen-map icon-container";
    markerIconContainer.appendChild(markerIcon)
    markerElement.appendChild(markerIconContainer);
    markerElement.appendChild(markerText);

    const { YMapMarker } = ymaps3;

    const marker = new YMapMarker({ coordinates: [buildingData.longitude, buildingData.latitude], }, markerElement);
    map.addChild(marker);

    globalMap = map;

    return markerElement;
}

let shownMobileCard = undefined;

export function addBuildingPreview(mapElement, marker, buildingData) {
    const buildingCard = document.createElement("div");
    const defaultClass = "full-screen-map building-card";
    const preview = document.createElement("img");
    const cardText = document.createElement("div");
    const title = document.createElement("div");
    const builder = document.createElement("div");
    const link = document.createElement("a");

    buildingCard.className = defaultClass;
    cardText.classList = "full-screen-map building-card-text";
    title.className = "building-cards name";
    builder.className = "building-cards group";
    link.className = "full-screen-map full-size-link";

    title.textContent = buildingData.name;
    builder.textContent = buildingData.builder;
    link.href = "/" + buildingData.code;

    if (buildingData.previewImage != '') {
        preview.loading = "lazy";
        buildingCard.appendChild(preview);
    }

    cardText.appendChild(title);
    cardText.appendChild(builder);
    buildingCard.appendChild(cardText);
    buildingCard.appendChild(link);

    buildingData.apartmentSpecifics.forEach(apartment => {
        const apartmentData = document.createElement("div");
        const title = document.createElement("div");
        const price = document.createElement("div");

        apartmentData.className = "full-screen-map building-card-line";
        title.className = "building-cards title-section";
        price.className = "building-cards description";

        title.textContent = apartment.name;
        price.textContent = apartment["min-price"];

        apartmentData.appendChild(title);
        apartmentData.appendChild(price);

        cardText.appendChild(apartmentData);
    });

    const mobileCard = buildingCard.cloneNode(true);
    mapElement.appendChild(mobileCard);
    mobileCard.className += " mobile";

    const gotoButton = document.createElement("a");
    const closeButton = document.createElement("div");
    const mobileButtons = document.createElement("div");
    const bipka = document.createElement("div");

    gotoButton.className = "common-button";
    gotoButton.textContent = "Перейти в ЖК";
    closeButton.className = "common-button";
    closeButton.textContent = "Закрыть";
    mobileButtons.className = "full-screen-map mobile-bottom-buttons";
    bipka.className = "full-screen-map mobile-handle";

    mobileButtons.appendChild(gotoButton);
    mobileButtons.appendChild(closeButton);
    mobileCard.appendChild(mobileButtons);
    mobileCard.appendChild(bipka);
    mobileCard.insertBefore(bipka, mobileCard.firstChild);

    gotoButton.href = link.href;
    closeButton.onclick = () => closeMobileCard();
    let isDraggingMap = false;

    marker.setAttribute("tabindex", -1);

    marker.addEventListener("click", (event) => {
        buildingCard.className = defaultClass + " visible";
        mobileCard.className = defaultClass + " mobile visible";
        const slash = buildingData.previewImage.startsWith("http") ? '' : '/';
        preview.src = `${slash}${buildingData.previewImage}`;

        const { YMapListener } = ymaps3;

        function onMapUnclicked(event) {
            if (!isDraggingMap) {
                buildingCard.className = defaultClass;
            }
        }

        const mapListener = new YMapListener({
            layer: 'any',
            onPointerUp: (event) => onMapUnclicked(event),
            onTouchEnd: (event) => onMapUnclicked(event),
            onActionStart: () => { isDraggingMap = true; },
            onActionEnd: () => { isDraggingMap = false; }
        });

        globalMap.addChild(mapListener);

        marker.onblur = (event) => {
            if ((event.relatedTarget != null && event.relatedTarget.className == marker.className)) {
                buildingCard.className = defaultClass;
            }
        }

        if (shownMobileCard != undefined) {
            shownMobileCard.className = defaultClass + " mobile";
        }

        shownMobileCard = mobileCard;
        shownMobileCard.querySelector("img").src = `${slash}${buildingData.previewImage}`;
        //Insert as last
        marker.parentElement.parentElement.lastChild.after(marker.parentElement);
        marker.focus();
    });

    let start = { x: 0, y: 0 };

    mobileCard.addEventListener("touchstart", touchStart, false);
    mobileCard.addEventListener("touchend", touchEnd, false);

    function touchStart(event) {
        start.x = event.changedTouches[0].pageX;
        start.y = event.changedTouches[0].pageY;
    }

    function touchEnd(event) {
        let offset = {};

        offset.x = start.x - event.changedTouches[0].pageX;
        offset.y = start.y - event.changedTouches[0].pageY;

        const requiredDistance = -Number.parseInt(mobileCard.clientHeight) / 8;

        if (offset.y < requiredDistance) {
            closeMobileCard();
        }

        return offset;
    }

    function closeMobileCard() {
        mobileCard.className = defaultClass + " mobile";
        shownMobileCard = undefined;
    }

    marker.appendChild(buildingCard);
    mapElement.appendChild(mobileCard);
}