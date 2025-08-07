import { TextInputCustomElement } from "./textInput";

export class PhoneInputCustomElement extends TextInputCustomElement {
    clearButton;
    input;

    constructor() {
        super();
    }

    load() {
        super.load();

        this.input.addEventListener("input", (event) => this.validatePhoneInput(event));
    }

    updateClearButtonVisibility() {
        this.clearButton.style.display = (this.input.value.length > 4) ? "block" : "none";
    }

    validatePhoneInput(event) {
        this.setAttribute("invalid", false);
        let match = event.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
        //match[0] is full value
        let prefix = match[1];
        let areaCode = match[2];
        let phonePart1 = match[3];
        let phonePart2 = match[4];
        let phonePart3 = match[5];
        let clearInput = "";

        if (event.target.value.length < 4) {
            event.target.value = "+7 (";
        }

        if (prefix != (undefined || "")) {
            clearInput += "+" + prefix;
        }

        if (areaCode != (undefined || "")) {
            clearInput += " (" + areaCode;

            if (areaCode.length >= 3) {
                clearInput += ") ";
            }
        }

        if (phonePart1 != (undefined || "")) {
            clearInput += phonePart1;

            if (phonePart1.length >= 3) {
                clearInput += "-";
            }
        }

        if (phonePart2 != (undefined || "")) {
            clearInput += phonePart2;

            if (phonePart2.length >= 2) {
                clearInput += "-";
            }
        }

        if (phonePart3 != (undefined || "")) {
            clearInput += phonePart3;
        }

        //Backspace and Del
        if (event.inputType != "deleteContentBackward" && event.inputType != "deleteContentForward") {
            event.target.value = clearInput;
        }
    }

    isValid() {
        let isValid = this.input.value.length > 18;
        this.setAttribute("invalid", !isValid);

        return isValid;
    }

    clear() {
        this.input.value = "+7 (";
        this.updateClearButtonVisibility();
    }
}