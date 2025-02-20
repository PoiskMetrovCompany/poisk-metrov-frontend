import axios from "axios";
import { loadBuildingCards } from "../buildingCard/loader";
import { loadPlanCardButtons } from "../planCard/loadPlanCardButtons";
import { Gallery } from "../gallery/gallery";
import { loadFavoritePlanStatusChange } from "./favoritePlanStatusChanged";
import { loadFavoriteBuildingStatusChange } from "./favoriteBuildingStatusChanged";
import { getCookie, setCookie } from "../cookies";

let favPlansGallery;
let favBuildingsGallery;

document.addEventListener("DOMContentLoaded", async () => {
    const comparePlansSwitchButton = document.getElementById("favorites-compare-plans-button");
    const compareBuildingsSwitchButton = document.getElementById("favorites-compare-buildings-button");

    if (comparePlansSwitchButton != null) {
        comparePlansSwitchButton.onclick = () => setComparisonSortingButton("fullComparisonPlans");
    }

    if (compareBuildingsSwitchButton != null) {
        compareBuildingsSwitchButton.onclick = () => setComparisonSortingButton("fullComparisonBuildings");
    }

    const setComparisonSortingButton = (cookieName) => {
        let isFullComparison = getCookie(cookieName);

        if (isFullComparison == null) {
            isFullComparison = "false";
        }

        isFullComparison = !(isFullComparison === "true");

        setCookie(cookieName, isFullComparison.toString(), 365);
        setCookie('lastFavoriteSelected', cookieName, 365);
        window.location.reload();
    }

    loadPlanCardButtons("expanded-plan-card container");
    loadPlanCardButtons();
    await loadBuildingCards("expanded-building-cards container");
    await loadBuildingCards();

    loadPlansGallery();
    loadFavoritePlanStatusChange();
    loadBuildingsGallery();
    loadFavoriteBuildingStatusChange();

    const galleries = [
        {
            dropdown: 'favorites-sorting-plans-dropdown',
            gallery: 'plans-gallery',
            url: '/api/favorite-plan-views',
            cardClass: 'expanded-plan-card container',
            onViewsLoaded: () => {
                loadPlanCardButtons("expanded-plan-card container");
                loadPlansGallery();
                loadFavoritePlanStatusChange();
            }
        },
        {
            dropdown: 'favorites-sorting-buildings-dropdown',
            gallery: 'complexes-gallery',
            url: '/api/favorite-building-views',
            cardClass: 'expanded-building-cards container',
            onViewsLoaded: async () => {
                await loadBuildingCards("expanded-building-cards container");
                loadBuildingsGallery();
                loadFavoriteBuildingStatusChange();
            }
        }
    ];

    const compactGalleries = [
        {
            dropdown: 'favorites-sorting-plans-dropdown',
            gallery: 'plans-gallery-compact',
            url: '/api/favorite-plan-views',
            cardClass: 'plan-card container',
            onViewsLoaded: () => {
                loadPlanCardButtons();
                loadFavoritePlanStatusChange();
            }
        },
        {
            dropdown: 'favorites-sorting-buildings-dropdown',
            gallery: 'complexes-gallery-compact',
            url: '/api/favorite-building-views',
            cardClass: 'building-cards container',
            onViewsLoaded: async () => {
                await loadBuildingCards();
                loadFavoriteBuildingStatusChange();
            }
        }
    ];

    galleries.forEach(gallery => loadSortingDropdown(gallery));
    compactGalleries.forEach(gallery => loadSortingDropdown(gallery));
});


function loadSortingDropdown(gallery) {
    const sortingDropdown = document.getElementById(gallery.dropdown);
    const galleryElement = document.getElementById(gallery.gallery);

    if (!sortingDropdown || !galleryElement) {
        return;
    }

    //TODO: переделать под NonSearchDropdownFilter
    const placeHolder = sortingDropdown.querySelector(".placeholder");
    const dropdown = sortingDropdown.querySelector(".custom-dropdown.base-container");
    const icon = sortingDropdown.querySelector(".icon.arrow-tailless");
    const items = Array.from(sortingDropdown.querySelectorAll(".names-dropdown.item"));
    const defaultDropdownClass = dropdown.className;

    if (!sortingDropdown) {
        return;
    }

    sortingDropdown.addEventListener("click", (event) => {
        if (event.target != dropdown) {
            if (!dropdown.className.includes("open")) {
                dropdown.className = defaultDropdownClass + " open";
                icon.style.rotate = "180deg";
            } else {
                dropdown.className = defaultDropdownClass;
                icon.style.rotate = "0deg";
            }
        }
    });

    sortingDropdown.addEventListener("focusout", (event) => {
        dropdown.className = defaultDropdownClass;
    });

    const sortingOptions = [
        {
            parameter: 'price',
            order: 'asc'
        },
        {
            parameter: 'price',
            order: 'desc'
        },
        {
            parameter: 'area',
            order: 'asc'
        },
        {
            parameter: 'area',
            order: 'desc'
        }
    ];

    items.forEach((item, i) => {
        item.addEventListener("click", async () => {
            placeHolder.textContent = item.textContent;
            galleryElement.style.opacity = 0.5;
            const params = new URLSearchParams(sortingOptions[i]);
            const response = await axios.get(`${gallery.url}?${params.toString()}`);
            const views = response.data?.views;

            galleryElement.style.opacity = 1;

            if (views) {
                const cards = Array.from(galleryElement.getElementsByClassName(gallery.cardClass));

                for (let i = 0; i < cards.length; i++) {
                    cards[i].remove();
                }

                views.forEach(view => {
                    galleryElement.innerHTML += view;
                });
            }

            await gallery.onViewsLoaded();
        });
    });
}

function loadPlansGallery() {
    if (favPlansGallery) {
        favPlansGallery = undefined;
    }

    const planGalleryID = "plans-gallery";
    const planGalleryButtons = "plans-gallery-buttons";
    favPlansGallery = new Gallery(planGalleryID, planGalleryButtons);
    favPlansGallery.isScrollOnClick = false;
    favPlansGallery.isButtonIcon = true;
    favPlansGallery.noDisable = true;

    document.getElementById(planGalleryID)?.scrollTo(0, 0);
}

function loadBuildingsGallery() {
    if (favBuildingsGallery) {
        favBuildingsGallery = undefined;
    }

    const buildingGalleryID = "complexes-gallery";
    const buildingGalleryButtons = "complexes-gallery-buttons";
    favBuildingsGallery = new Gallery(buildingGalleryID, buildingGalleryButtons, () => { }, "orange", true);
    favBuildingsGallery.isScrollOnClick = false;
    favBuildingsGallery.isButtonIcon = true;
    favBuildingsGallery.noDisable = true;
    favBuildingsGallery.buttonBack?.setAttribute("enabled", true);
    favBuildingsGallery.buttonForward?.setAttribute("enabled", true);

    document.getElementById(buildingGalleryID)?.scrollTo(0, 0);
}