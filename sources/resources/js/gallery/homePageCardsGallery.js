import { Gallery } from "./gallery";

document.addEventListener('DOMContentLoaded', () => {
    const galleryID = "catalogue-grid";
    const galleryButtons = "catalogue-grid-buttons";
    const newGallery = new Gallery(galleryID, galleryButtons);
    newGallery.noDisable = true;
    newGallery.isScrollOnClick = false;
})