import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    const ids = ["logout-button", "logout-button-mobile"]

    ids.forEach(id => {
        const logout = document.getElementById(id);

        if (logout) {
            logout.addEventListener("click", async () => {
                await axios.post("/api/log-out", {});
                window.location.reload();
            });
        }
    });
});