import { BuildingCard } from "./buildingCard";

export class AgentBuildingCard extends BuildingCard {

    async load() {
        this.imageGallery = this.querySelector("image-gallery");

        const more = this.querySelector("[type=more]");
        const description = this.querySelector("[type=description-container]");

        more.addEventListener("click", () => {
            description.setAttribute("showmore", description.getAttribute("showmore") != "true");
        });

        this.loadMap();
    }

    connectedCallback() {
        setTimeout(() => this.load())
    }
}