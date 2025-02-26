import { setCookie, getCookie, eraseCookie } from "../cookies";
import axios from "axios";

//TODO: think about waiting for likes to resolve
export async function likePlan(offerId, likeButton, plan) {
    const favoriteCookieString = getCookie("favoritePlans");
    let favoriteCookies = [];

    const likeForm = new FormData();
    likeForm.set("code", offerId);
    likeForm.set("type", "apartment");

    if (favoriteCookieString) {
        favoriteCookies = favoriteCookieString.split(',');
    }

    if (!favoriteCookies.includes(offerId)) {
        favoriteCookies.push(offerId);
        likeButton.className += " orange";
        likeForm.set("action", "add");
    } else {
        favoriteCookies = favoriteCookies.filter(storedOffer => storedOffer != offerId);
        likeButton.className = "plan-card card-button";
        likeForm.set("action", "remove");
    }
    
    eraseCookie("cachedFavoritePlansCount");

    if (favoriteCookies.length > 0) {
        setCookie("favoritePlans", favoriteCookies.join(','), 365);
    } else {
        eraseCookie("favoritePlans");
    }

    const likeCount = await axios.post("/api/switch-like", likeForm);
    const likeEvent = new Event("likesUpdated");
    likeEvent['newPlanCount'] = Number.parseInt(likeCount.data.plans);
    likeEvent['newBuildingCount'] = Number.parseInt(likeCount.data.buildings);
    likeEvent['newCount'] = Number.parseInt(likeCount.data.total);
    likeEvent['code'] = offerId;
    likeEvent['action'] = likeForm.get("action");
    likeEvent['button'] = likeButton;

    likeEvent['apartment_type'] = plan.getAttribute("apartment_type");
    likeEvent['area'] = plan.getAttribute("area");
    likeEvent['price'] = plan.getAttribute("price");

    document.dispatchEvent(likeEvent);
}