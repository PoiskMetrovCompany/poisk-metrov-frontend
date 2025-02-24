import { CRMForm } from './CRMForm';

document.addEventListener("DOMContentLoaded", () => {
    new CRMForm(
        "signup-for-buildng-form",
        "signup-for-building-buttons",
        "/api/leave-request",
        ["name", "last_name", "middle_name", "phone", "conscent-checkbox"],
        (socialNetworkName) => {
            let callType = "Звонок";

            if (socialNetworkName != null) {
                callType = socialNetworkName;
            }

            return "Заказан звонок - " + document.getElementById("building-name").textContent + ", способ - " + callType;
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const success = popup.querySelector(".success");
            popup.style.visibility = "visible";
            success.style.display = "grid";

            const oldPopup = document.getElementById("signup-base");

            if (oldPopup) {
                oldPopup.style.visibility = "hidden";
            }
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const failure = popup.querySelector(".failure");
            popup.style.visibility = "visible";
            failure.style.display = "grid";

            const oldPopup = document.getElementById("signup-base");

            if (oldPopup) {
                oldPopup.style.visibility = "hidden";
            }
        }
    );
})
