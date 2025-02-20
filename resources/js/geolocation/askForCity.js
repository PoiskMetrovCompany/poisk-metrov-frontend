import { getCookie, setCookie } from "../cookies";

document.addEventListener("DOMContentLoaded", () => {
    // Should be done with separate window when city detection works again
    // Also save in local storage, not in cookies
    return;
    const cityWasSelected = getCookie('cityWasSelected');

    if (cityWasSelected != 'true') {
        //Make sure menu.js is loaded before this
        const citySelectionButton = document.getElementById("current-city-mobile-button");
        const fakeClick = new Event("click");
        citySelectionButton.dispatchEvent(fakeClick);

        setCookie('cityWasSelected', 'true', 365);
    }
});