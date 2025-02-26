import { eraseCookie, getCookie, setCookie } from "../cookies";

export class LikeButton extends HTMLElement {
    constructor() {
        super();
    }

    load() {
        if (this.getAttribute("for") == "plan") {
            this.addEventListener("click", () => this.likePlan());
        } else {
            this.addEventListener("click", () => this.likeBuilding());
        }
    }

    async likePlan() {

    }

    async likeBuilding() {
        const buildingCode = this.getAttribute("code");
        const favoriteCookieString = getCookie("favoriteBuildings");
        const removeFavoriteCookieString = getCookie("removedFavoriteBuildings");
        let favoriteCookies = [];

        const likeForm = new FormData();
        likeForm.set("code", buildingCode);
        likeForm.set("type", "building");

        if (favoriteCookieString) {
            favoriteCookies = favoriteCookieString.split(',');
        }

        if (!favoriteCookies.includes(buildingCode)) {
            favoriteCookies.push(buildingCode);
            this.setAttribute("like", 1);
            likeForm.set("action", "add");
        } else {
            favoriteCookies = favoriteCookies.filter(storedOffer => storedOffer != buildingCode);
            this.setAttribute("like", 0);
            likeForm.set("action", "remove");
            let removed = [];

            if (removeFavoriteCookieString) {
                removed = removeFavoriteCookieString.split(',');
            }

            removed.push(buildingCode);

            setCookie("removedFavoriteBuildings", removed.join(','), 365);
        }

        eraseCookie("cachedFavoriteBuildingsCount");

        if (favoriteCookies.length > 0) {
            setCookie("favoriteBuildings", favoriteCookies.join(','), 365);
        } else {
            eraseCookie("favoriteBuildings");
        }

        const likeCount = await axios.post("/api/switch-like", likeForm);
        const likeEvent = new Event("likesUpdated");
        likeEvent['newPlanCount'] = Number.parseInt(likeCount.data.plans);
        likeEvent['newBuildingCount'] = Number.parseInt(likeCount.data.buildings);
        likeEvent['newCount'] = Number.parseInt(likeCount.data.total);
        likeEvent['code'] = buildingCode;
        likeEvent['action'] = likeForm.get("action");
        likeEvent['button'] = this;

        const buildingCard = document.getElementById(buildingCode);

        likeEvent['name'] = buildingCard.getAttribute("buildingname");
        likeEvent['metro'] = buildingCard.getAttribute("metro");
        likeEvent['metrominutes'] = buildingCard.getAttribute("metrominutes");
        likeEvent['metromoveicon'] = buildingCard.getAttribute("metromoveicon");

        document.dispatchEvent(likeEvent);
    }

    connectedCallback() {
        this.load();
    }
}