import { openPopup, closePopup } from "./popupActivity";

document.addEventListener("DOMContentLoaded", () => {
    const quizContainer = document.querySelector(".quiz.container");

    if (!quizContainer) {
        return;
    }

    document.getElementById("quiz-popup-open")?.addEventListener("click", () => {
        openPopup('quiz');
        quizContainer.getElementsByTagName("iframe")[0].src = "https://mrqz.me/64f82d13fafc5f00256566be";
    });
    document.getElementById("quiz-popup-close-tint")?.addEventListener("click", () => closePopup('quiz'));
    document.getElementById("quiz-popup-close-cross")?.addEventListener("click", () => closePopup('quiz'));
});