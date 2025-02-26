import { likePlan } from "../favorites/likePlan";
import { sharePageMenu } from "../pageShare/sharePageMenu";
import { loadChart } from "../realEstate/chart";

export function loadPlanCardButtons(cardClassName = "plan-card container") {
    const planCards = Array.from(document.getElementsByClassName(cardClassName));

    planCards.forEach(plan => {
        const offerId = plan.getAttribute("offerId");

        const buttons = plan.getElementsByClassName("plan-card card-button");
        const likeButton = buttons[0];
        likeButton.onclick = () => likePlan(offerId, likeButton, plan);

        const shareButton = buttons[1];
        const id = shareButton.id;
        
        loadChart(plan);
        sharePageMenu(id, () => window.location.host + "/" + offerId);
    });
}