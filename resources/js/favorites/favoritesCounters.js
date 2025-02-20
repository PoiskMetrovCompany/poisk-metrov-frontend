document.addEventListener("DOMContentLoaded", () => {
    const favoriteBuildingCounter = document.getElementById("favorite-building-counter");
    const favoriteApartmentCounter = document.getElementById("favorite-apartment-counter");

    document.addEventListener("likesUpdated", (event) => {
        if (favoriteApartmentCounter) {
            favoriteApartmentCounter.textContent = event.newPlanCount;
        }

        if (favoriteBuildingCounter) {
            favoriteBuildingCounter.textContent = event.newBuildingCount;
        }
    });
});