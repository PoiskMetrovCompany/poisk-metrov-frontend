export function cardGallery(cardElement, noLimit = false, noSwitchOnHover = false) {
    const background = document.getElementById("background-" + cardElement.id);
    const previews = document.getElementById("previews_" + cardElement.id).value.split(",").slice(0, noLimit ? 1000 : 5);
    const cardArea = cardElement.getElementsByClassName("card-area-container")[0];
    const cardResizeObserver = new ResizeObserver(() => updateCardArea());

    let start = { x: 0, y: 0 };
    let currentSlider = 0;
    cardResizeObserver.observe(cardArea.parentElement);
    updateCardArea();

    function updateCardArea() {
        cardArea.style.position = "absolute";
        const style = window.getComputedStyle(cardArea.parentElement);
        const padding = style.padding;
        cardArea.style.width = style.width;
        cardArea.style.height = style.height
        cardArea.style.transform = `translate(-${padding},-${padding})`;
    }

    background.addEventListener("touchstart", touchStart, false);
    background.addEventListener("touchend", touchEnd, false);

    function touchStart(event) {
        start.x = event.changedTouches[0].pageX;
        start.y = event.changedTouches[0].pageY;
    }

    function touchEnd(event) {
        let offset = {};

        offset.x = start.x - event.changedTouches[0].pageX;
        offset.y = start.y - event.changedTouches[0].pageY;

        const requiredDistance = Number.parseInt(cardArea.style.width) / 3;

        if (offset.x > requiredDistance) {
            currentSlider++;
            currentSlider = Math.min(Math.max(currentSlider, 0), previews.length - 1);
            slide(currentSlider);
        }
        if (offset.x < -requiredDistance) {
            currentSlider--;
            currentSlider = Math.min(Math.max(currentSlider, 0), previews.length - 1);
            slide(currentSlider);
        }

        return offset;
    }

    let sliders = [];
    let areas = [];
    if (previews.length > 0) {
        for (let i = 0; i < previews.length; i++) {
            const slidersElement = document.getElementById("indicator_" + cardElement.id + "_" + i);

            if (slidersElement) {
                sliders.push(slidersElement);
            }

            const areaElement = document.getElementById("area_" + cardElement.id + "_" + i);

            if (areaElement) {
                areas.push(areaElement);

                if (!noSwitchOnHover) {
                    areas[i].addEventListener('mouseover', () => slide(i));
                } else {
                    areas[i].addEventListener('click', () => slide(i));
                }
            }
        }

        if (sliders[0]) {
            sliders[0].className += ' active';
        }

        //No sliders if only one picture
        if (!sliders[1] && sliders[0]) {
            sliders[0].style.display = "none";
        }
    }

    function slide(id) {
        if (previews.length > 1) {
            if (previews[id].startsWith("http")) {
                background.style.backgroundImage = "url(" + previews[id] + ")";
            } else {
                background.style.backgroundImage = "url(/" + previews[id] + ")";
            }
            setSliderActive(id);
        }
    }

    function setSliderActive(id) {
        sliders.forEach(slider => slider.className = "slider-indicator");
        sliders[id].className += " active";
        currentSlider = id;
    }
}