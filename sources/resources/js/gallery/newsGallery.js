import { Gallery } from "./gallery";

document.addEventListener("DOMContentLoaded", () => {
    const galleryID = 'news-cards';
    const galleryButtons = 'news-buttons';
    const newGallery = new Gallery(galleryID, galleryButtons);
});