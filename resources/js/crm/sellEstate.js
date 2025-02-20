import { CRMForm } from "./CRMForm";
import { getCustomSelect } from "../customDropdown";

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("sell-building-form");

    new CRMForm(
        "sell-building-form",
        undefined,
        "/api/leave-request",
        ["name", "phone", "conscent-checkbox"],
        () => {
            const buildingTypeSelect = getCustomSelect('Не выбран', 'building-type');
            const serviceTypeSelect = getCustomSelect('Не выбрана', 'service-type');
            return "Тип недвижимости - " + buildingTypeSelect + ", тип услуги - " + serviceTypeSelect;
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