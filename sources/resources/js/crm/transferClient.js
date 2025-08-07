import { CRMForm } from './CRMForm';
import { getInputByLegendName } from '../getInputByLegendName';
import { getCustomSelect } from '../customDropdown';

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("transfer-client");

    new CRMForm(
        "transfer-client",
        undefined,
        "/api/leave-request",
        ["name", "phone", "conscent-checkbox"],
        () => {
            const clientNameInput = getInputByLegendName(form, "Имя клиента");
            const clientPhoneInput = getInputByLegendName(form, "Телефон клиента");
            const clientCityInput = getCustomSelect('Не выбран', 'city');

            return "Передача клиента " + clientCityInput + ", " + clientNameInput.value + ", " + clientPhoneInput.value;
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
});