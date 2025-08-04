document.addEventListener("DOMContentLoaded", () => {
    let checkboxes = Array.from(document.getElementsByClassName("checkbox-borders"));
    checkboxes.forEach(checkbox => checkbox.addEventListener("click", () => transferClickToCheckbox(checkbox)))
})

export function transferClickToCheckbox(checkboxOwner) {
    let checkbox = checkboxOwner.querySelector('input[type="checkbox"]');

    if (checkbox != null) {
        //Кэшируем результат специально для Firefox
        let newResult = !checkbox.checked;
        checkbox.checked = newResult;
        const submitButton = checkboxOwner.parentElement.parentElement.parentElement.querySelector('input[type="submit"]');

        if (!submitButton)
            return;

        if (submitButton.name == "footer-submit-button")
            return;

        if (submitButton.className.includes("modal")) {
            submitButton.className = (newResult) ? "peinag button modal active" : "peinag button modal";
        } else {
            submitButton.className = (newResult) ? "peinag button active" : "peinag button";
        }    
    }
}