import { CRMForm } from './CRMForm';

document.addEventListener("DOMContentLoaded", () => {
    let currentSocialNetwork = null;
    new CRMForm(
        "learn-about-first-sale",
        "learn-about-first-sale-buttons",
        "/api/leave-request",
        ["name", "last_name", "middle_name", "phone", "conscent-checkbox"],
        (socialNetworkName) => {
            currentSocialNetwork = socialNetworkName;
            if (socialNetworkName == "Звонок" || socialNetworkName == null) {
                return "Узнать о новых стартах продаж первым, контакт - Звонок";
            } else {
                return "Узнать о новых стартах продаж первым, контакт - " + socialNetworkName;
            }
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const success = popup.querySelector(".success");
            popup.style.visibility = "visible";
            success.style.display = "grid";

            const presentationMenu = document.getElementById("learn-about-first-sale-menu");
            if (presentationMenu) {
                presentationMenu.style.display = "none";
            }
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const failure = popup.querySelector(".failure");
            popup.style.visibility = "visible";
            failure.style.display = "grid";

            const presentationMenu = document.getElementById("learn-about-first-sale-menu");
            if (presentationMenu) {
                presentationMenu.style.display = "none";
            }
        }
    );
})
