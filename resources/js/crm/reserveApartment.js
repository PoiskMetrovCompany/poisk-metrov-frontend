import { CRMForm } from "./CRMForm";

document.addEventListener("DOMContentLoaded", () => {
    let currentSocialNetwork = null;
    new CRMForm(
        "reserve-apartment-form",
        "reserve-apartment-buttons",
        "/api/leave-request",
        ["name", "last_name", "middle_name", "conscent-checkbox", "make-meeting-form"],
        (socialNetworkName) => {
            currentSocialNetwork = socialNetworkName;
            if (socialNetworkName == "Звонок" || socialNetworkName == null) {
                return "!!!Бронь - " + document.getElementById("building-name").textContent + ", кв. " + document.getElementById("apartment-number").textContent + " - Звонок";
            } else {
                return "!!!Бронь - " + document.getElementById("building-name").textContent + ", кв. " + document.getElementById("apartment-number").textContent + " - " + socialNetworkName;
            }

        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const success = popup.querySelector(".success");
            popup.style.visibility = "visible";
            success.style.display = "grid";

            const reserveApartment = document.getElementById("reserve-apartment");
            if (reserveApartment) {
                reserveApartment.style.display = "none";
            }
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const failure = popup.querySelector(".failure");
            popup.style.visibility = "visible";
            failure.style.display = "grid";

            const reserveApartment = document.getElementById("reserve-apartment");
            if (reserveApartment) {
                reserveApartment.style.display = "none";
            }
        }
    );
});
