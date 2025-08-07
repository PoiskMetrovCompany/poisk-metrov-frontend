import { CRMForm } from './CRMForm';

document.addEventListener("DOMContentLoaded", () => {
    let currentSocialNetwork = null;
    new CRMForm(
        "learn-mortgage-form",
        "learn-mortgage-form-buttons",
        "/api/leave-request",
        ["name", "phone", "conscent-checkbox"],
        (socialNetworkName) => {
            currentSocialNetwork = socialNetworkName;
            if (socialNetworkName == "Звонок" || socialNetworkName == null) {
                return "Узнать больше об ипотеке, контакт - Звонок";
            } else {
                return "Узнать больше об ипотеке, контакт - " + socialNetworkName;
            }
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const success = popup.querySelector(".success");
            popup.style.visibility = "visible";
            success.style.display = "grid";

            const presentationMenu = document.getElementById("learn-mortgage");
            if (presentationMenu) {
                presentationMenu.style.display = "none";
            }
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const failure = popup.querySelector(".failure");
            popup.style.visibility = "visible";
            failure.style.display = "grid";

            const presentationMenu = document.getElementById("learn-mortgage");
            if (presentationMenu) {
                presentationMenu.style.display = "none";
            }
        }
    );
})