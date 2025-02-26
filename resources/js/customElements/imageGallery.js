export class ImageGallery extends HTMLElement {
    spritePositions = [];
    previews;
    cardArea;
    sliders;
    areas;
    currentSlider;
    start;

    constructor() {
        super();
        this.start = { x: 0, y: 0 };
    }

    load() {
        let noLimit = false;
        let noSwitchOnHover = false;
        this.previews = JSON.parse(this.getAttribute("spritepositions")).slice(0, noLimit ? 1000 : 5);
        this.cardArea = this.querySelector("*[type=card-area]");

        this.currentSlider = 0;

        this.addEventListener("touchstart", (event) => this.touchStart(event), false);
        this.addEventListener("touchend", (event) => this.touchEnd(event), false);

        this.sliders = Array.from(this.querySelector("*[type=slider-indicators]").querySelectorAll("div"));
        let areas = [];

        if (this.previews.length > 0) {
            for (let i = 0; i < this.previews.length; i++) {
                const areaElement = this.cardArea.children[i];

                if (areaElement) {
                    areas.push(areaElement);

                    if (!noSwitchOnHover) {
                        areas[i].addEventListener('mouseover', () => this.slide(i));
                    } else {
                        areas[i].addEventListener('click', () => this.slide(i));
                    }
                }
            }

            if (this.sliders[0]) {
                this.sliders[0].setAttribute('active', true);
            }

            //No this.sliders if only one picture
            if (!this.sliders[1] && this.sliders[0]) {
                this.sliders[0].style.display = "none";
            }
        }
    }

    touchStart(event) {
        this.start.x = event.changedTouches[0].pageX;
        this.start.y = event.changedTouches[0].pageY;
    }

    touchEnd(event) {
        let offset = {};

        offset.x = this.start.x - event.changedTouches[0].pageX;
        offset.y = this.start.y - event.changedTouches[0].pageY;

        const requiredDistance = Number.parseInt(this.cardArea.clientWidth) / 4;

        if (offset.x > requiredDistance) {
            this.currentSlider++;
            this.currentSlider = Math.min(Math.max(this.currentSlider, 0), this.previews.length - 1);
            this.slide(this.currentSlider);
        }
        if (offset.x < -requiredDistance) {
            this.currentSlider--;
            this.currentSlider = Math.min(Math.max(this.currentSlider, 0), this.previews.length - 1);
            this.slide(this.currentSlider);
        }
    }

    slide(num) {
        if (this.previews.length <= 1) {
            return;
        }

        const previewObject = this.previews[num];
        let x = 0;
        let y = 0;

        x = previewObject.x;

        if (previewObject.size_x < this.clientWidth) {
            const mult = this.clientWidth / previewObject.size_x;
            this.style.backgroundSize = `auto ${previewObject.size_y * mult}px`;
            x *= mult;
            y = previewObject.size_y / mult / 2;
        } else {
            this.style.backgroundSize = '';
            const sizeOffset = (previewObject.size_x - this.clientWidth) / 2;
            x += sizeOffset;
            //TODO: fix some pictures not taking up all space if clientHeight > size_y
            x *= Math.max(1, this.clientHeight / previewObject.size_y);
        }

        this.style.backgroundPositionX = `-${x}px`;
        // this.style.backgroundPositionY = `-${y}px`;
        this.setSliderActive(num);
    }

    setSliderActive(id) {
        this.sliders.forEach(slider => slider.setAttribute('active', false));
        this.sliders[id].setAttribute('active', true);
        this.currentSlider = id;
    }

    connectedCallback() {
        setTimeout(() => this.load())
    }
}