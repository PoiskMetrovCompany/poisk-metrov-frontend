export class MobileMenu extends HTMLElement {
    constructor() {
        super();
    }

    load() {
        const menuButtonIds = ["mobile-menu-open"];

        menuButtonIds.forEach(id => {
            const menuButton = document.getElementById(id);

            menuButton?.addEventListener("click", () => {
                this.style.display = this.style.display === "grid" ? "none" : "grid";
                menuButton.setAttribute("selected", this.style.display === "grid");
            });
        });

        const citySelect = this.querySelector(".mobile-menu.city-select");
        const icon = citySelect.querySelector(".icon.arrow-tailless");    
        const cities = Array.from(this.querySelectorAll(".mobile-menu.city-select.item.unselected"));

        citySelect.addEventListener("click", () => {
            icon.style.transform = icon.style.transform == "rotate(0deg)" ? "rotate(180deg)" : "rotate(0deg)";
            cities.forEach(city => city.style.display = city.style.display == "flex" ? "none" : "flex");
        });
    }

    connectedCallback() {
        setTimeout(() => this.load())
    }
}