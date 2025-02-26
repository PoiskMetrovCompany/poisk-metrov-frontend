import { likeBuilding } from "../favorites/likeBuilding";

export function loadLikeButton(buildingCard) {
    const buildingCode = buildingCard.id;
    const likeButton = document.getElementById(buildingCode + "-like-button");

    likeButton.onclick = async () => {
        await likeBuilding(buildingCode, likeButton);
    }
}