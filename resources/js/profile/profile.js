import axios from "axios";
import { validateName } from "../inputPatternValidation/name";

function loadProfilePage() {
    const profileForm = document.getElementById("profile-form");

    if (!profileForm) {
        return;
    }

    profileForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        const checkFields = validateName(profileForm);

        if (!checkFields) {
            return;
        }

        let userInfo = new FormData(event.target);
        await axios.post("/api/update-profile", userInfo);
        window.location.reload();
    });
}

document.addEventListener("DOMContentLoaded", () => loadProfilePage());