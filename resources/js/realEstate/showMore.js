document.addEventListener("DOMContentLoaded", () => {
    const button = document.getElementById("show-more");
    const hideButton = document.getElementById("hide-more");

    if (button) {
        button.addEventListener("click", () => {
            document.getElementById("complex-description-full").style = "";
            document.getElementById("complex-description").style.display = "none";
        });
    }

    if (hideButton) {
        hideButton.addEventListener("click", () => {
            document.getElementById("complex-description").style = "";
            document.getElementById("complex-description-full").style.display = "none";
        })
    }
});