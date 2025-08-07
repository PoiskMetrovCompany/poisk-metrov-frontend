document.addEventListener("DOMContentLoaded", () => {
    const statusDropdown = document.getElementById("client-status-dropdown");
    const residentialComplexDropdown = document.getElementById("residential-complex-dropdown");
    const clientRequestDropdown = document.getElementById("client-request-dropdown");
    const clientTimeDropdown = document.getElementById("client-time-dropdown");
    const clientShowTypeDropdown = document.getElementById("client-show-type-dropdown");

    document.querySelector(".client-register.header").querySelector("h1").querySelector("button").addEventListener("click", () => history.back());

    clientRequestDropdown.onAfterOptionSelected = (customOption) => {
        const optionText = customOption.textContent.trim();

        switch (optionText) {
            case "Фиксация":
                clientRequestDropdown.parentElement.parentElement.querySelector(".client-register.show-fixation").classList.add("shown");
                break;
            case "Заявка на показ":
                clientRequestDropdown.parentElement.parentElement.querySelector(".client-register.show-request").classList.add("shown");
                break;
        }
    }

    clientRequestDropdown.onAfterOptionDeselected = (customOption) => {
        const optionText = customOption.textContent.trim();

        switch (optionText) {
            case "Фиксация":
                clientRequestDropdown.parentElement.parentElement.querySelector(".client-register.show-fixation").classList.remove("shown");
                break;
            case "Заявка на показ":
                clientRequestDropdown.parentElement.parentElement.querySelector(".client-register.show-request").classList.remove("shown");
                break;
        }
    }

    const commerceCheckbox = document.getElementById("commerce-checkbox");
    const parkingCheckbox = document.getElementById("parking-checkbox");
    const storagesCheckbox = document.getElementById("storages-checkbox");

    commerceCheckbox.onStatusChanged = (event) => { }
    parkingCheckbox.onStatusChanged = (event) => { }
    storagesCheckbox.onStatusChanged = (event) => { }

    const clientRegisterForm = document.getElementById("client-register-form");

    clientRegisterForm.addEventListener("submit", (event) => {
        event.preventDefault();

    });

    const calendar = document.getElementById("show-date-calendar");
});