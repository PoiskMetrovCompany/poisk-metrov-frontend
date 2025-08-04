import { Gallery } from "./gallery";

document.addEventListener("DOMContentLoaded", (event) => {
    const galleryID = "about-building-locations";
    const galleryButtons = "about-building-gallery-buttons";
    new Gallery(galleryID, galleryButtons);
})