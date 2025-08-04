import { CRMForm } from './CRMForm';
import { downloadFile } from '../downloadFile';

document.addEventListener("DOMContentLoaded", () => {
    let currentSocialNetwork = null;
    new CRMForm(
        "get-free-catalogue-form",
        "get-catalogue-buttons",
        "/api/leave-request-without-name",
        ["phone", "conscent-checkbox"],
        (socialNetworkName) => {
            currentSocialNetwork = socialNetworkName;

            if (socialNetworkName == "Скачать на сайте" || socialNetworkName == null) {
                return "Скачать каталог на сайте";
            } else {
                return "Отправить каталог в " + socialNetworkName;
            }
        },
        () => {
            if (currentSocialNetwork == null)
                downloadFile("placeholders/image.png", "presentation.png");
            const popup = document.getElementById("thanks-for-contacts");
            const success = popup.querySelector(".success");
            popup.style.visibility = "visible";
            success.style.display = "grid";

            const form = document.getElementById("get-free-catalogue-form");

            if (form) {
                form.parentElement.style.display = "none";
            }
        },
        () => {
            const popup = document.getElementById("thanks-for-contacts");
            const failure = popup.querySelector(".failure");
            popup.style.visibility = "visible";
            failure.style.display = "grid";

            const form = document.getElementById("get-free-catalogue-form");

            if (form) {
                form.parentElement.style.display = "none";
            }
        }
    );

    const buttons = document.getElementById("get-catalogue-buttons");

    if (!buttons) {
        return;
    }

    const buttonsOnForm = Array.from(buttons.children);
    const outsideWhatsappButton = document.getElementById("get-catalogue-whatsapp");
    const outsideTelegramButton = document.getElementById("get-catalogue-telegram");

    function transferClick(button) {
        const event = new Event("click");
        button.dispatchEvent(event);
    }

    outsideWhatsappButton?.addEventListener("click", () => transferClick(buttonsOnForm[0]));
    outsideTelegramButton?.addEventListener("click", () => transferClick(buttonsOnForm[1]));
});