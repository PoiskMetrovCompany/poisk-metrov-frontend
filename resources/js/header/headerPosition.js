import { isProfileMenuOpen } from "../authorization/profileButton";

window.addEventListener("load", () => {
    const header = document.getElementById("top-bar");

    if (!header) {
        return;
    }

    let cachedPositionY = 0;
    let cachedScrollDelta = 0;
    let scrollDelta = 0;
    let isAnimationOver = true;

    let showHeader = function () {
        header.style.animation = "slideDown 0.35s ease-in";
        header.style.position = "sticky";
        header.onanimationend = function () {
            isAnimationOver = true;
        }
    }

    let hideHeader = function () {
        header.style.animation = "slideUp 0.35s ease-out";
        header.onanimationend = function () {
            header.style.position = "initial";
            isAnimationOver = true;
        }
    }

    let checkHeaderState = function () {
        if (!isAnimationOver) {
            return;
        }

        let currentPositionY = document.documentElement.scrollTop || document.body.scrollTop;
        if (currentPositionY < header.scrollHeight && !isProfileMenuOpen) {
            header.style.animation = "none";
            header.style.position = "initial";
            return;
        }
        scrollDelta = cachedPositionY - currentPositionY;
        if (scrollDelta > 0 && cachedScrollDelta < 0 || scrollDelta < 0 && cachedScrollDelta > 0) {
            if (scrollDelta - cachedScrollDelta > 0 && !isProfileMenuOpen) {
                hideHeader();
            }
            else {
                showHeader();
            }
        }
        cachedPositionY = currentPositionY;
        cachedScrollDelta = scrollDelta;
    }

    document.addEventListener("scroll", () => checkHeaderState());
})