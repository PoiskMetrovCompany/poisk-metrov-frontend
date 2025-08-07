let displayTimeout;
let defaultClassName;
let popup;

document.addEventListener("DOMContentLoaded", () => {
    let favoritesPopups = Array.from(document.querySelectorAll(".added-popup.base-container"));

    favoritesPopups = favoritesPopups.slice(0, 1);

    favoritesPopups.forEach(currentPopup => {
        const closeButton = currentPopup.querySelector(".icon.action-close");
        popup = currentPopup;

        defaultClassName = currentPopup.className;
        closeButton.onclick = () => closePopup();
    });
});

document.addEventListener("likesUpdated", (likeEvent) => {
    if (likeEvent.action == "add") {
        const content = popup.querySelector(".added-popup.content");
        const title = content.querySelector(".added-popup.title");
        const price = content.querySelector(".added-popup.price");
        const metro = content.querySelector(".building-cards.description.one-line");
        const spans = content.getElementsByTagName("span");
        const stationName = spans[0];
        const transportIcon = spans[1];
        const transportMinutes = spans[2];

        clearTimer();

        popup.className += " visible";

        if (likeEvent.area) {
            title.textContent = likeEvent.apartment_type + ' ' + likeEvent.area;
            price.textContent = likeEvent.price;
            metro.style.display = "none";
            price.style.display = "block";
        } else {
            title.textContent = likeEvent.name;
            stationName.textContent = likeEvent.metro;
            transportIcon.className = `icon d24x24 ${likeEvent.metromoveicon} orange`;
            transportMinutes.textContent = likeEvent.metrominutes;
            metro.style.display = "flex";
            price.style.display = "none";
        }

        if (likeEvent.metro == "") {
            metro.style.display = "none";
        }

        displayTimeout = setTimeout(() => closePopup(), 10000);
    }
});

function clearTimer() {
    if (displayTimeout != undefined) {
        clearTimeout(displayTimeout);
        displayTimeout = undefined;
    }
}

function closePopup() {
    popup.className = defaultClassName;
    clearTimer();
}