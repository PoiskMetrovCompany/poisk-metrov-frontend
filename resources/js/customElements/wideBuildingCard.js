import { BuildingCard } from "./buildingCard";

export class WideBuildingCard extends BuildingCard {
    constructor() {
        super();
    }

    async load() {
        this.imageGallery = this.querySelector("image-gallery");

        const more = this.querySelector("[type=more]");
        const description = this.querySelector("[type=description-container]");

        more.addEventListener("click", () => {
            description.setAttribute("showmore", description.getAttribute("showmore") != "true");
        });
    }

    connectedCallback() {
        setTimeout(() => this.load());
    }
}
