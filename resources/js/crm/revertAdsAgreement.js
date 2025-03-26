import { CRMForm } from "./CRMForm";

document.addEventListener("DOMContentLoaded", () => {
    const url = "/api/revert-ads-agreement";
    const formPopup = document.getElementById('revert-ads-form');

    const onSuccess = () => {
        const phoneInput = formPopup.querySelector('input[type="tel"]');
        const phone = phoneInput.value;
        const popup = document.getElementById("ads-agreement-reverted");
        popup.style.display = "grid";
        const popupTitle = popup.querySelector(".sub-menus.title");
        popupTitle.textContent = 'Номер ' + phone + ' исключен из списка рассылок рекламной информации.';
        const revertRequest = document.getElementById("revert-ads-agreement");
        console.log(popup.style.visibility);
        if (popup.style.visibility === 'hidden') {
            popup.style.visibility = 'visible';
        }

        if (revertRequest) {
            revertRequest.style.display = "none";
        }
    }

    const onFailure = () => {
        const popup = document.getElementById("ads-agreement-reverted");
        popup.style.visibility = "visible";
        const popupTitle = popup.querySelector(".sub-menus.title");
        popupTitle.textContent = 'Произошла ошибка';
        const revertRequest = document.getElementById("revert-ads-agreement");
        if (revertRequest) {
            revertRequest.style.display = "none";
        }
    }

    const inputs = ["phone"];

    new CRMForm(
        "revert-ads-form",
        undefined,
        url,
        inputs,
        () => {},
        onSuccess,
        onFailure
    );
})
