import { CRMForm } from './CRMForm';

document.addEventListener("DOMContentLoaded", () => {
    const url = "/api/leave-request";
    const onSuccess = () => {
        const popup = document.getElementById("thanks-for-contacts");
        popup.style.visibility = "visible";
        const success = popup.querySelector(".success");
        success.style.display = "grid";

        const consultRequest = document.getElementById("consult-request");
        if (consultRequest) {
            consultRequest.style.display = "none";
        }
    }

    const onFailure = () => {
        const popup = document.getElementById("thanks-for-contacts");
        popup.style.visibility = "visible";
        const failure = popup.querySelector(".failure");
        failure.style.display = "grid";

        const consultRequest = document.getElementById("reserve-apartment");
        if (consultRequest) {
            consultRequest.style.display = "none";
        }
    }

    const inputs = ["name", "phone", "conscent-checkbox"];
    let currentSocialNetwork = null;

    new CRMForm(
        "consult-request-form",
        "consult-request-buttons",
        url,
        inputs,
        (socialNetworkName) => {
            currentSocialNetwork = socialNetworkName;
            if (socialNetworkName == "Звонок" || socialNetworkName == null) {
                return "Заявка с сайта - Звонок";
            } else {
                return "Заявка с сайта - " + socialNetworkName;
            }
        },
        onSuccess,
        onFailure
    );

    new CRMForm(
        "leave-contacts-form",
        undefined,
        url,
        inputs,
        () => "Обратный звонок",
        onSuccess,
        onFailure
    );
});