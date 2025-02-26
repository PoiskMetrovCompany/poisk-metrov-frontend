import { cardGallery } from "../buildingCard/gallery";
import { sharePageMenu } from "../pageShare/sharePageMenu";
import { updateVisitedPages } from "../updateVisitedPages";

document.addEventListener("DOMContentLoaded", () => {
    const topContent = document.getElementById("top-content");
    const galleryElement = document.getElementById(topContent.getAttribute("code"));
    cardGallery(galleryElement, true);
    sharePageMenu("share-page-button-mobile");
    sharePageMenu("share-page-button");
    updateVisitedPages('lastVisitedBuildings');
});