import { CRMForm } from './CRMForm';

document.addEventListener("DOMContentLoaded", () => {
    let currentSocialNetwork = null;

    const meetingMenu = document.getElementById("make-meeting");
    
    new CRMForm(
        "make-meeting-form",
        "make-meeting-buttons",
        "/api/leave-request",
        ["name", "phone", "conscent-checkbox"],
        (socialNetworkName) => {
            currentSocialNetwork = socialNetworkName;
            const officeAddress = meetingMenu.querySelector("#office-address").value;
            if (socialNetworkName == "Звонок" || socialNetworkName == null) {
                return "Записаться на встречу " + officeAddress +", контакт - Звонок";
            } else {
                return "Записаться на встречу, контакт - " + socialNetworkName;
            }
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const success = popup.querySelector(".success");
            popup.style.visibility = "visible";
            success.style.display = "grid";

            if (meetingMenu) {
                meetingMenu.style.display = "none";
            }
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const failure = popup.querySelector(".failure");
            popup.style.visibility = "visible";
            failure.style.display = "grid";

            if (meetingMenu) {
                meetingMenu.style.display = "none";
            }
        }
    );
});