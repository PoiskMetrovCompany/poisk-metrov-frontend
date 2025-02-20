import { Gallery } from "./gallery";

document.addEventListener("DOMContentLoaded", (event) => {
    const galleryID = "gallery-images-container";
    const galleryButtons = "primary-gallery-buttons";
    const gallery = document.getElementById(galleryID);
    const primaryGalleryImage = document.getElementById("gallery-first-image");
    new Gallery(galleryID, galleryButtons,
        (elementNumber, firstLoad) => {
            const galleryImage = gallery.getElementsByTagName("img")[elementNumber];
            if (primaryGalleryImage.src != galleryImage.src) {
                primaryGalleryImage.src = galleryImage.src;
                if (!firstLoad){
                    const header = document.getElementById("top-bar");
                    header.style.animation = "slideUp 0.35s ease-out";
                    header.onanimationend = function () {
                        header.style.position = "initial";
                    }
                    location.hash = "gallery-anchor";
                }
            }
        });
})