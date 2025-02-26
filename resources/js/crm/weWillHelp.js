import { CRMForm } from './CRMForm';

document.addEventListener("DOMContentLoaded", () => {
    new CRMForm(
        "call-me-back-form",
        undefined,
        "/api/leave-request-without-name",
        ["phone"],
        () => {
            return 'Запрос с формы "Найдем квартиру. Поможем с ипотекой."'
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const success = popup.querySelector(".success");
            popup.style.visibility = "visible";
            success.style.display = "grid";
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const failure = popup.querySelector(".failure");
            popup.style.visibility = "visible";
            failure.style.display = "grid";
        }
    );
})