import { ceil10 } from "../decimalAdjust";

export class Slider {
    slider;
    display;
    maxDisplay;
    data;
    propertyDisplay;

    constructor(elementID, displayID, maxDisplayID, propertyDisplay, step, onInput, onBeforeUpdate) {
        this.slider = document.getElementById(elementID);
        this.display = document.getElementById(displayID);
        this.maxDisplay = document.getElementById(maxDisplayID);
        this.display.step = step;
        this.slider.step = step;
        this.propertyDisplay = propertyDisplay;

        this.display.addEventListener("change", () => {
            const spaceLessCurrent = this.display.value.split(' ').join('');
            const value = Number.parseFloat(spaceLessCurrent);

            //Doesn't work on number input element
            if (this.display.value != "") {
                if (Number.parseInt(value) < Number.parseInt(this.display.min)) {
                    this.display.value = this.display.min;
                }

                if (Number.parseInt(value) > Number.parseInt(this.display.max)) {
                    this.display.value = this.display.max;
                }
            }
            else {
                this.display.value = this.display.min;
            }

            if (onBeforeUpdate != undefined) {
                onBeforeUpdate();
            }

            const spaceLess = this.display.value.split(' ').join('');
            this.display.value = this.addSpaces(Number.parseFloat(spaceLess));
            this.syncDataFromInput();
            onInput();
        });

        this.display.addEventListener("input", () => {
            const spaceLess = this.display.value.split(' ').join('');
            let number = Number.parseFloat(spaceLess);

            if (isNaN(number)) {
                number = 0;//this.slider.min;
            }

            this.display.value = this.addSpaces(number);
        });

        this.slider.addEventListener("input", () => {
            if (onBeforeUpdate != undefined) {
                onBeforeUpdate();
            }

            this.syncDataFromSlider();
            onInput();
        });
    }

    addSpaces(number) {
        let numberAsString = number.toString().split('');
        let offset = numberAsString.length % 3;
        let numberWithSpaces = '';
        let pos = 0;

        for (let i = 0; i < numberAsString.length; i++) {
            numberWithSpaces += numberAsString[i];
            pos = offset - i % 3 - 1;

            if (pos == 0 || pos == -3) {
                numberWithSpaces += ' '
            }
        }

        return numberWithSpaces.trim();
    }

    getDisplayInfo(withSpaces = false) {
        if (withSpaces) {
            return this.addSpaces(this.data[this.propertyDisplay]);
        } else {
            return this.data[this.propertyDisplay];
        }
    }

    getMaxDisplayInfo() {
        return this.slider.max;
    }

    syncDataFromInput() {
        this.data[this.propertyDisplay] = ceil10(Number.parseFloat(this.display.value.split(' ').join('')), -2);
        this.slider.value = this.getDisplayInfo();
        this.maxDisplay.textContent = this.getMaxDisplayInfo();
    }

    syncDataFromSlider() {
        this.data[this.propertyDisplay] = ceil10(Number.parseFloat(this.slider.value), -2);
        this.display.value = this.getDisplayInfo(true);
        this.maxDisplay.textContent = this.getMaxDisplayInfo();
    }

    load(newData, min, value, max) {
        this.data = newData;
        this.slider.min = min;
        this.slider.max = max;
        this.slider.value = value;
        // this.slider.parentElement.parentElement.style.opacity = min < max ? 1 : 0.5;
        // this.slider.parentElement.style.pointerEvents = min < max ? "all" : "none";
        // this.slider.parentElement.parentElement.style.cursor = min < max ? "default" : "not-allowed";
        this.display.min = min;
        this.display.max = max;
        this.syncDataFromSlider();
        this.maxDisplay.textContent = this.getMaxDisplayInfo();
    }
}