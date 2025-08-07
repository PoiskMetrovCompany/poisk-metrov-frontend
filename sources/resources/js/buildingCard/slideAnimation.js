export function slideAnimation(cardElement) {
    const background = document.getElementById("background-" + cardElement.id);
    const more = document.getElementById("more_" + cardElement.id);
    const description = document.getElementById("full-description-" + cardElement.id);

    if (!background || !more || !description) {
        return;
    }

    let isSlideUp = false;
    let isInTransition = false;

    description.addEventListener("mouseover", () => slideUp(true));
    description.addEventListener("mouseleave", () => slideDown(true));
    description.addEventListener("click", switchSlide);

    function switchSlide() {
        if (isInTransition) {
            return;
        }

        if (isSlideUp) {
            slideDown();
        }
        else {
            slideUp();
        }
    }

    function slideUp(forced = false) {
        if ((isInTransition || isSlideUp) && !forced) {
            return;
        }
        description.style.maxHeight = "100%";
        background.style.maxHeight = "28%";
        more.style.display = "none";
        if (!forced) {
            isInTransition = true;
            //Transition end events are too unreliable, using timeouts instead
            setTimeout(() => {
                isSlideUp = true;
                isInTransition = false;
            }, 350);
        }
    }

    function slideDown(forced = false) {
        if ((isInTransition || !isSlideUp) && !forced) {
            return;
        }
        description.style.maxHeight = "40%";
        background.style.maxHeight = "55%";
        more.style.display = "grid";
        if (!forced) {
            isInTransition = true;
            setTimeout(() => {
                isSlideUp = false;
                isInTransition = false;
            }, 350);
        }
    }
}