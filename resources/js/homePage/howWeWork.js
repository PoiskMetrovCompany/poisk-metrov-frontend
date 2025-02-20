import { Gallery } from "../gallery/gallery";


document.addEventListener("DOMContentLoaded", () => {
    const galleryID = "aboutus-gallery";
    const galleryButtons = "aboutus-gallery-buttons";
    const baseContainer = document.querySelector(".how-work-gallery.work-base-container");
    const titles = Array.from(baseContainer.querySelectorAll(".how-work-gallery.work-title"));
    const newGallery = new Gallery(galleryID, galleryButtons, (elementIndex) => {
        titles.forEach((title, i) => title.style.display = elementIndex == i ? "block" : "none");
    }, 'orange', true);
});