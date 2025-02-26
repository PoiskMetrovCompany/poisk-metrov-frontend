import { CRMForm } from './CRMForm';

export let downloadPresentationMenu = null;

document.addEventListener("DOMContentLoaded", () => {
    let currentSocialNetwork = null;
    downloadPresentationMenu = new CRMForm(
        "get-real-estate-presentation",
        "get-presentation-buttons",
        "/api/leave-request",
        ["name", "phone", "conscent-checkbox"],
        (socialNetworkName) => {
            currentSocialNetwork = socialNetworkName;
            let buildingName = document.getElementById("building-name").textContent;
            if (socialNetworkName == "Скачать на сайте" || socialNetworkName == null) {
                return "Скачать презентацию на сайте - " + buildingName;
            } else {
                return "Выслать презентацию комплекса в " + socialNetworkName + " - " + buildingName;
            }
        },
        () => {
            if (currentSocialNetwork == null && currentPresentationLink != "" && currentPresentationLink != "none") {
                window.location = currentPresentationLink;
            }
            const popup = document.getElementById("thanks-for-contacts");
            const success = popup.querySelector(".success");
            popup.style.visibility = "visible";
            success.style.display = "grid";

            const presentationMenu = document.getElementById("get-real-estate-presentation-menu");
            if (presentationMenu) {
                presentationMenu.style.display = "none";
            }
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const failure = popup.querySelector(".failure");
            popup.style.visibility = "visible";
            failure.style.display = "grid";

            const presentationMenu = document.getElementById("get-real-estate-presentation-menu");
            if (presentationMenu) {
                presentationMenu.style.display = "none";
            }
        }
    );
})