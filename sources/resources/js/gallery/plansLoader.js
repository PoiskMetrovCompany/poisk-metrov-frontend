import { Gallery } from "./gallery";

document.addEventListener("DOMContentLoaded", () => {
    const galleryID = "plans-grid";
    const galleryButtons = "plans-grid-buttons";
    const newGallery = new Gallery(galleryID, galleryButtons);
    newGallery.isScrollOnClick = false;
})