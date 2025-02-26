import { likePlan } from "./likePlan";

export function loadFavoritePlanStatusChange() {
    const planCards = Array.from(document.getElementsByClassName("expanded-plan-card container"));

    planCards.forEach(plan => {
        const card = plan.querySelector(".favorites-deleted.card");
        const offerId = plan.getAttribute("offerid");
        const buttons = card.querySelectorAll(".favorites-deleted.description");
        const restore = buttons[0];
        const deleteCompletely = buttons[1];

        let likeButton;

        restore.onclick = async () => {
            if (likeButton != undefined) {
                await likePlan(offerId, likeButton, plan);
            }
        };

        let interval = undefined;

        deleteCompletely.onclick = () => {
            if (interval != undefined) {
                return;
            }

            let lowerOpacity = () => {
                plan.style.opacity -= 0.02;

                if (plan.style.opacity <= 0) {
                    clearInterval(interval);
                    plan.remove();
                    interval = undefined;
                }
            }

            plan.style.opacity = 1;
            interval = setInterval(lowerOpacity, 1);
        }

        document.addEventListener("likesUpdated", (likeEvent) => {
            if (likeEvent.code == offerId) {
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