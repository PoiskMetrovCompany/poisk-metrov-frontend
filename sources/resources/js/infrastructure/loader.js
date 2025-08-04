import { loadInfrastructureMap } from "./infrastructureLoader";
import { initializeInfrastructureMenu } from "./infrastructureMenu";

document.addEventListener("DOMContentLoaded", () => {
    initializeInfrastructureMenu(document.getElementById("location-menu"));
    initializeInfrastructureMenu(document.getElementById("infrastructure-menu"));
    loadInfrastructureMap();
});