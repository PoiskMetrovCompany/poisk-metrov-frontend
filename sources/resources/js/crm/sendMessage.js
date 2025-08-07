import { CRMForm } from "./CRMForm";

document.addEventListener("DOMContentLoaded", () => {

    new CRMForm(
        "left-message",
        undefined,
        "/api/leave-request",
        ["name", "last_name", "middle_name", "phone", "conscent-checkbox"],
        () => {
            const message = document.getElementById("message");
            return "Сообщение - " + message.value;
        },
        () => {
            const messageForm = document.getElementById("left-message");
            const resultContainer = document.querySelector(".offices.message.result");
            const success = resultContainer.querySelector(".success");
            messageForm.style.display = "none";
            success.style.display = "grid";
        },
        () => {
            const messageForm = document.getElementById("left-message");
            const resultContainer = document.querySelector(".offices.message.result");
            const failure = resultContainer.querySelector(".failure");
            messageForm.style.display = "none";
            failure.style.display = "grid";
        }
    );
});
