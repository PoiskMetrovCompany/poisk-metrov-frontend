document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("thanks-for-contacts")?.addEventListener("click", () => closePopup('thanks-for-contacts'));
    document.getElementById("ads-agreement-reverted")?.addEventListener("click", () => closePopup('ads-agreement-reverted'));
});

export function closePopup(popupID) {
    const element = document.getElementById(popupID)
    if (element != undefined) {
        element.style.visibility = "hidden";
    }
}

export function openPopup(popupID) {
    const element = document.getElementById(popupID)
    if (element != undefined) {
        element.style.visibility = "visible";
    }
}