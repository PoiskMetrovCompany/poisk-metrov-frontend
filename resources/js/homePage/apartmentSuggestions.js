import { Gallery } from "../gallery/gallery";
import { loadPlanCardButtons } from "../planCard/loadPlanCardButtons";

document.addEventListener("DOMContentLoaded", () => {
    const galleryID = "apartment-suggestions-cards";
    const galleryButtons = "apartment-suggestions-buttons";
    const newGallery = new Gallery(galleryID, galleryButtons);
    newGallery.isScrollOnClick = false;
    loadPlanCardButtons();
});