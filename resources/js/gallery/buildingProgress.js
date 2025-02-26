import { Gallery } from "./gallery";

document.addEventListener("DOMContentLoaded", (event) => {
    const galleryID = "building-progress-gallery";
    const galleryButtons = "building-progress-gallery-buttons";
    new Gallery(galleryID, galleryButtons);
})