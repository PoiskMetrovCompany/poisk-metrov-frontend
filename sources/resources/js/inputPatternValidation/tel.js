document.addEventListener("DOMContentLoaded", () => {
    let inputs = Array.from(document.getElementsByTagName("input")).filter(input => input.parentElement.parentElement.parentElement.tagName.toLowerCase() != "phone-input");
    inputs.forEach(input => {
        if (input.attributes.getNamedItem("type")?.value == "tel") {
            const clearIcon = input.parentElement.querySelector('.icon');
            const container = input.parentElement.parentElement.parentElement;

            input.addEventListener("focus", (e) => {
                if (e.target.value === (undefined || ""))
                    e.target.value = "+7 (";
            });

            clearIcon?.addEventListener("click", () => {
                input.value = "";

                setTimeout(() => {
                    input.focus();
                }, 10);
            });

            input.addEventListener("input", (e) => {
                let match = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
                //match[0] is full value
                let prefix = match[1];
                let areaCode = match[2];
                let phonePart1 = match[3];
                let phonePart2 = match[4];
                let phonePart3 = match[5];
                let clearInput = "";
                if (e.target.value.length < 2) {
                    e.target.value = "+7";
                }
                if (prefix != (undefined || ""))
                    clearInput += "+" + prefix;
                if (areaCode != (undefined || "")) {
                    clearInput += " (" + areaCode;
                    if (areaCode.length >= 3)
                        clearInput += ") ";
                }
                if (phonePart1 != (undefined || "")) {
                    clearInput += phonePart1;
                    if (phonePart1.length >= 3)
                        clearInput += "-";
                }
                if (phonePart2 != (undefined || "")) {
                    clearInput += phonePart2;
                    if (phonePart2.length >= 2)
                        clearInput += "-";
                }
                if (phonePart3 != (undefined || ""))
                    clearInput += phonePart3;
                //Backspace and Del
                if (e.inputType != "deleteContentBackward" && e.inputType != "deleteContentForward")
                    e.target.value = clearInput;
            });

            clearIcon?.addEventListener("mousedown", () => {
                const event = new Event("focusin");
                container.dispatchEvent(event);
                input.value = "";

                setTimeout(() => {
                    input.focus();
                }, 10);
            })
        }
    })
})

export function validatePhone(form) {
    let inputs = Array.from(form.querySelectorAll('#phone'));
    let res = true;
    inputs.forEach(input => {
        const container = input.parentElement.parentElement;
        const errorMessage = container.parentElement.querySelector(".input-error");
        const errorIcon = input.parentElement.querySelector(".error-icon");

        if (input.value.length < 18) {
            container.className = "input-fieldset highlighted";
            errorMessage.style.display = "grid";
            errorIcon.style.display = "grid";
            input.className = "input-text error";
            res = res && false;
        } else {
            container.className = "input-fieldset";
            errorMessage.style.display = "none";
            errorIcon.style.display = "none";
            input.className = "input-text";
            res = res && true;
        }
    });

    return res;
}