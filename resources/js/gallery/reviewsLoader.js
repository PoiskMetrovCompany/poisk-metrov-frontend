import { Gallery } from "./gallery";

document.addEventListener("DOMContentLoaded", () => {
    const galleryID = "reviews-gallery";
    const galleryButtons = "reviews-gallery-buttons";
    const newGallery = new Gallery(galleryID, galleryButtons);
})