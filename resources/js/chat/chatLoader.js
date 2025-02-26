import { isMobile } from "../utils/isMobile";
import { Chat } from "./chat";

document.addEventListener("DOMContentLoaded", () => {
    const button = document.getElementById("open-chat-button");
    const chatWindow = document.querySelector(".chat-window.base-container");
    const chat = new Chat(chatWindow);
    const isMobileScreen = isMobile();
    const wasClosedDuringSession = new Boolean(sessionStorage.getItem('chatWasClosed')).valueOf();
    let wasClicked = false;

    button.addEventListener("click", () => {
        chat.switch();
        wasClicked = true;
    });

    const minutes = 1;
    const secondsInMinute = 60;
    const millisecondsInSecond = 1000;

    setTimeout(() => {
        if (!wasClicked && !isMobileScreen && wasClosedDuringSession != true) {
            chat.show();
        }
    }, minutes * secondsInMinute * millisecondsInSecond);
});