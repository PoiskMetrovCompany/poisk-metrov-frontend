export function validateLastName(form) {
    let inputs = Array.from(form.querySelectorAll('.input-text'));
    let res = true;
    inputs.forEach(input => {
        if (input.attributes.getNamedItem('type')?.value == 'text' && input.attributes.getNamedItem('last_name')?.value != 'phone') {
            let checkField = !(/\d/.test(input.value)) || input.value.length == 0;
            const container = input.parentElement.parentElement;
            const errorMessage = container.parentElement.querySelector(".input-error");
            const errorIcon = input.parentElement.querySelector(".error-icon");

            if (errorIcon && errorMessage) {
                if (!checkField) {
                    container.className = "input-fieldset highlighted";
                    errorMessage.style.display = "grid";
                    errorIcon.style.display = "grid";
                    input.className = "input-text error";
                } else {
                    container.className = "input-fieldset";
                    errorMessage.style.display = "none";
                    errorIcon.style.display = "none";
                    input.className = "input-text";
                }
            }

            res = res && checkField;
        }

    });
    return res;
}

document.addEventListener("DOMContentLoaded", () => {
    let inputs = Array.from(document.getElementsByTagName("input"));

    inputs.forEach(input => {
        const type = input.attributes.getNamedItem('type');

        if (!type) {
            return;
        }

        if (type?.value == 'text' && type?.value != 'phone' || type?.value == 'email') {
            const clearIcon = input.parentElement.querySelector(".icon.action-close");

            if (clearIcon) {
                clearIcon.addEventListener("mousedown", () => {
                    input.value = "";

                    setTimeout(() => {
                        input.focus();
                    }, 10);
                });
            }
        }
    })
})
