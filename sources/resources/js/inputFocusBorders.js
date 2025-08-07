document.addEventListener("DOMContentLoaded", () => {
    let inputs = Array.from(document.getElementsByTagName("input"));
    const validTypes = ["tel", "text", "email"]

    inputs.forEach(input => {
        const inputType = input.attributes.getNamedItem("type")?.value;

        if (!validTypes.includes(inputType) ||
            input.parentElement.className.startsWith("agent") ||
            input.parentElement.parentElement.className.startsWith("agent") ||
            input.parentElement.parentElement.parentElement.tagName.toLowerCase() == "phone-input" ||
            input.parentElement.parentElement.parentElement.tagName.toLowerCase() == "text-input") {
            return;
        }

        input.setAttribute("tabindex", -1);
        const style = window.getComputedStyle(input);
        const copy = ['borderColor', 'color'];
        const container = input.parentElement.parentElement.parentElement;
        const errorMessage = container.querySelector('.input-error');
        const errorIcon = input.parentElement.querySelector('.error-icon');
        const clearIcon = input.parentElement.querySelector('.icon.action-close');

        container.addEventListener("focusin", (e) => {
            copy.forEach(copied => {
                if (style[copied]) {
                    input.parentElement.parentElement.style[copied] = style[copied];

                    if (clearIcon && errorIcon) {
                        clearIcon.style.display = "grid";
                        errorIcon.style.display = "none";
                    }
                }
            });
        });

        container.addEventListener("focusout", (e) => {
            input.parentElement.parentElement.style = "";
            if (clearIcon)
                clearIcon.style.display = "none";
        });

        input.addEventListener("input", () => {
            if (errorMessage)
                errorMessage.style.display = "none";
        })
    })
})