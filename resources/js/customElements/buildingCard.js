import { loadMap } from "../buildingCard/map";
import { sleep } from "../sleep";

let map;
let ymaps3;
let mapContainer;
let isMapLoaded = false;
let deselectors = [];

export class BuildingCard extends HTMLElement {
    imageGallery;
    link;

    constructor() {
        super();
    }

    async load() {
        this.imageGallery = this.querySelector("image-gallery");
        this.querySelector("share-button").getLink = () => `${window.location.host}/${this.getAttribute("id")}`;
        this.slideAnimation();
        this.loadMap();
    }

    async loadMap() {
        let wait = true;

        if (!isMapLoaded) {
            [map, ymaps3, mapContainer] = await loadMap("new-temp-map-container");

            isMapLoaded = true;
        }

        if (ymaps3) {
            if (wait) {
                await sleep(10);
            }

            const deselector = await this.setMap(map, mapContainer, () => deselectors.forEach(deselector => deselector()));
            deselectors.push(deselector);
        }
    }

    slideAnimation() {
        const background = this.querySelector("[type=top-content]");
        const more = this.querySelector("[type=more]");
        const description = this.querySelector("[type=description-container]");
        const additionalDescription = this.querySelector("[type=additional-info]");

        if (!background || !more || !description) {
            return;
        }

        let isSlideUp = false;
        let isInTransition = false;

        description.addEventListener("mouseover", () => slideUp(true));
        description.addEventListener("mouseleave", () => slideDown(true));
        description.addEventListener("click", switchSlide);

        function switchSlide() {
            if (isInTransition) {
                return;
            }

            if (isSlideUp) {
                slideDown();
            } else {
                slideUp();
            }
        }

        function slideUp(forced = false) {
            if ((isInTransition || isSlideUp) && !forced) {
                return;
            }

            description.style.maxHeight = "100%";
            // background.style.maxHeight = "28%";
            more.style.display = "none";
            additionalDescription.style.display = "grid";

            if (!forced) {
                isInTransition = true;
                setTimeout(() => {
                    isSlideUp = true;
                    isInTransition = false;
                }, 350);
            }
        }

        function slideDown(forced = false) {
            if ((isInTransition || !isSlideUp) && !forced) {
                return;
            }

            // description.style.maxHeight = "45%";
            background.style.maxHeight = "55%";
            more.style.display = "grid";
            additionalDescription.style.display = "";

            if (!forced) {
                isInTransition = true;

                setTimeout(() => {
                    isSlideUp = false;
                    isInTransition = false;
                }, 350);
            }
        }
    }

    async setMap(map, yMapElement, deselectAll) {
        const tempMapContainer = document.getElementById("new-temp-map-container");
        const mapButton = this.querySelector("div[type=top-buttons]").querySelector("button");
        const mapContainer = this.querySelector("div[type=map]");
        const longitude = Number.parseFloat(this.getAttribute("long"));
        const latitude = Number.parseFloat(this.getAttribute("lat"));
        const coordinates = [longitude, latitude];
        const zoom = 17;
        const thisCard = this;

        let marker;

        mapContainer.style.display = "none";
        mapButton.onclick = () => {
            if (mapContainer.style.display == "none") {
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
                mapContainer.style.width = mapContainer.parentElement.clientWidth + 4 + "px";
                mapContainer.style.height = mapContainer.parentElement.clientHeight + 4 + "px";
                tempMapContainer.style.width = mapContainer.clientWidth + 4 + "px";
                tempMapContainer.style.height = mapContainer.clientHeight + 4 + "px";
            }
        }

        const resizer = new ResizeObserver(adjustMapSize);
        resizer.observe(mapContainer.parentElement);
        resizer.observe(document.body);

        function select() {
            mapContainer.style.display = "block";
            mapButton.setAttribute("mapActive", true);

            map.update({
                location:
                {
                    center: coordinates,
                    zoom: zoom
                }
            });

            marker = thisCard.addBuildingMarker(map, coordinates);
            mapContainer.appendChild(yMapElement);
            adjustMapSize();
        }

        function deselect() {
            mapContainer.style.display = "none";
            mapButton.setAttribute("mapActive", false);

            if (marker) {
                map.removeChild(marker);
            }

            tempMapContainer.appendChild(yMapElement);
        }

        const deselector = () => deselect();

        return deselector;
    }

    addBuildingMarker(map, coordinates) {
        const markerElement = document.createElement("div");
        markerElement.className = "full-screen-map icon-container";
        markerElement.style.width = '2.8em';
        markerElement.style.height = '2.8em';
        const iconElement = document.createElement("div");
        iconElement.className = "icon place d44x44 white";
        markerElement.appendChild(iconElement);
        const { YMapMarker } = ymaps3;
        const marker = new YMapMarker({ coordinates: coordinates, }, markerElement);
        map.addChild(marker);

        return marker;
    }

    connectedCallback() {
        setTimeout(() => this.load())
    }
}

const userAgent = navigator.userAgent || navigator.vendor || window.opera;

async function mobileLoadAnimation() {
    if (/android|iphone|ipad|ipod|mobile/i.test(userAgent) || /tablet|ipad/i.test(userAgent)) {
        console.log("Пользователь зашел с мобильного устройства или планшета");
        let buildingCard = new BuildingCard();
        await buildingCard.load();
    }
}
