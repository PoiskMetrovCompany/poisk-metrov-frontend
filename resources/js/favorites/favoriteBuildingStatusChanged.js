import { likeBuilding } from "./likeBuilding";

export function loadFavoriteBuildingStatusChange() {
    const buildingCards = Array.from(document.getElementsByClassName("expanded-building-cards container"));

    buildingCards.forEach(building => {
        const card = building.querySelector(".favorites-deleted.card");
        const code = building.getAttribute("id");
        const buttons = card.querySelectorAll(".favorites-deleted.description");
        const restore = buttons[0];
        const deleteCompletely = buttons[1];

        let likeButton;

        restore.onclick = async () => {
            if (likeButton != undefined) {
                await likeBuilding(code, likeButton);
            }
        };

        let interval = undefined;

        deleteCompletely.onclick = () => {
            if (interval != undefined) {
                return;
            }

            let lowerOpacity = () => {
                building.style.opacity -= 0.02;

                if (building.style.opacity <= 0) {
                    clearInterval(interval);
                    building.remove();
                    interval = undefined;
                }
            }

            building.style.opacity = 1;
            interval = setInterval(lowerOpacity, 1);
        }

        document.addEventListener("likesUpdated", (likeEvent) => {
            if (likeEvent.code == code) {
                if (likeEvent.action == "remove") {
                    card.className = "favorites-deleted card visible";
                } else {
                    card.className = "favorites-deleted card";
                }

                likeButton = likeEvent.button;
            }
        });
    });
}