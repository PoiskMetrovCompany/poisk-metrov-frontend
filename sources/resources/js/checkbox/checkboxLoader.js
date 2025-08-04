import { Checkbox } from "./Checkbox";

document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll(".pseudo-checkbox");

    checkboxes.forEach(checkbox => new Checkbox(checkbox));
});