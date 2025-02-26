import { setCookie, getCookie, eraseCookie } from "../cookies";
import axios from "axios";

export async function likeBuilding(buildingCode, likeButton) {
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
        likeButton.className += " orange";
        likeForm.set("action", "add");
    } else {
        favoriteCookies = favoriteCookies.filter(storedOffer => storedOffer != buildingCode);
        likeButton.className = "building-cards card-button";
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
    likeEvent['button'] = likeButton;

    const buildingCard = document.getElementById(buildingCode);

    likeEvent['name'] = buildingCard.getAttribute("buildingname");
    likeEvent['metro'] = buildingCard.getAttribute("metro");
    likeEvent['metrominutes'] = buildingCard.getAttribute("metrominutes");
    likeEvent['metromoveicon'] = buildingCard.getAttribute("metromoveicon");

    document.dispatchEvent(likeEvent);
}