import { loadOfficesMap} from "./loadMap";

document.addEventListener("DOMContentLoaded", () => {
    const officeLocationsNsk = [[82.97524499999993, 55.03895456965725], [82.9226394999999, 55.06297756965984]];
    const officeLocationsSpb = [[30.31244549999996, 59.90215356423468]];
    loadOfficesMap(officeLocationsNsk, 'map-nsk');
    loadOfficesMap(officeLocationsSpb, 'map-spb');

    const nskOffices = document.getElementById("nsk");
    const spbOffices = document.getElementById("spb");

    displayOffices(nskOffices);
    displayOffices(spbOffices);
});

function displayOffices(activeOffices) {
    let officeHeaders = Array.from(activeOffices.getElementsByClassName("offices dropdown-container"));
    let offices = Array.from(activeOffices.getElementsByClassName("offices office-container"));
    let showButtons = Array.from(activeOffices.querySelectorAll(".arrow-chevron-right"));
    let hideButtons = Array.from(activeOffices.querySelectorAll(".arrow-down"));

    officeHeaders.forEach((header, i) => {
        let show = header.querySelector(".arrow-chevron-right");
        let hide = header.querySelector(".arrow-down");

        header.addEventListener("click", () => {
            if (offices[i].style.display === "grid") {
                show.style.display = "grid";
                hide.style.display = "none";
                offices[i].style.display = "none";
            } else {
                offices.forEach(office => {
                    office.style.display = "none";
                });
                showButtons.forEach(button => {
                    button.style.display = "grid";
                });
                hideButtons.forEach(button => {
                    button.style.display = "none";
                });
                
                show.style.display = "none";
                hide.style.display = "grid";
                offices[i].style.display = "grid";
            }
        });
    });
}