import { cardGallery } from "../buildingCard/gallery";
import { sharePageMenu } from "../pageShare/sharePageMenu";
import { loadPlanCardButtons } from "../planCard/loadPlanCardButtons";
import { updateVisitedPages } from "../updateVisitedPages";

document.addEventListener("DOMContentLoaded", () => {
    const topContent = document.getElementById("top-content");
    const galleryElement = document.getElementById(topContent.getAttribute("code"));
    cardGallery(galleryElement, true, true);
    sharePageMenu("share-apartment-button");

    loadPlanCardButtons();
    updateVisitedPages('lastVisitedApartments');
});