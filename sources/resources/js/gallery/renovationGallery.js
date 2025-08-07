import { Gallery } from "./gallery";

document.addEventListener("DOMContentLoaded", (event) => {
    const galleryID = "renovation-gallery";
    const galleryButtons = "renovation-gallery-buttons";
    new Gallery(galleryID, galleryButtons, () => { }, "black");
})