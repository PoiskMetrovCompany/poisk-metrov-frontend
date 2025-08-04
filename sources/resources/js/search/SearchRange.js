import { setRangeData, setRangeDisplay } from "../filters/simpleRange";

export class SearchRange {
    rangeParent;
    range = null;
    rangeDisplay = null;
    startMinValue = null;
    startMaxValue = null;
    currentMinValue = 0;
    currentMaxValue = 0;
    tiedDropdown;

    lastKnob;
    minKnob;
    maxKnob;

    constructor(elementId, tiedDropdown, startValues = [0, 0]) {
        this.tiedDropdown = tiedDropdown;
        this.rangeParent = document.getElementById(elementId);
        this.range = document.getElementById(elementId + "-selector");
        this.rangeDisplay = this.rangeParent.querySelector(".number-input-flex");
        this.startMinValue = Number.parseFloat(this.range.getAttribute("min-range"));
        this.startMaxValue = Number.parseFloat(this.range.getAttribute("max-range"));

        const disabled = this.range.getAttribute("disabled") == "1";

        if (disabled) {
            return;
        }

        if (startValues[0] == 0) {
            this.currentMinValue = this.startMinValue;
        } else {
            this.currentMinValue = startValues[0];
        }

        if (startValues[1] == 0) {
            this.currentMaxValue = this.startMaxValue;
        } else {
            this.currentMaxValue = startValues[1];
        }

        this.loadRange();

        document.addEventListener("searchItemsCleared", () => this.resetSlider());
        document.addEventListener("parameterLoadedFromSearch", (event) => this.onParameterLoadedFromSearch(event));
        // document.addEventListener("searchItemRemoved", (event) => this.resetValue(event));
    }

    loadRange() {
        setRangeData(
            this.range,
            this.rangeDisplay,
            this.startMinValue,
            this.startMaxValue,
            (filter, min, max) => this.onRangeFilterUpdated(filter, min, max),
            (value) => this.formatValueForFilter(value)
        );
        this.range.setAttribute("preset-min", this.currentMinValue);
        this.range.setAttribute("preset-max", this.currentMaxValue);

        const eventNames = ["click", "touch", "mousedown", "change", "input"];
        this.minKnob = this.range.shadowRoot.getElementById("min");
        this.maxKnob = this.range.shadowRoot.getElementById("max");

        eventNames.forEach(eventName => {
            this.minKnob.addEventListener(eventName, () => this.lastKnob = this.minKnob);
            this.maxKnob.addEventListener(eventName, () => this.lastKnob = this.maxKnob);
        });

        const inputEventNames = ["input", "change"];

        inputEventNames.forEach(inputEventName => {
            this.minKnob.addEventListener(inputEventName, (event) => {
                setRangeDisplay(this.rangeDisplay, this.formatValueForFilter(this.minKnob.value), this.formatValueForFilter(this.maxKnob.value));
            });

            this.maxKnob.addEventListener(inputEventName, (event) => {
                setRangeDisplay(this.rangeDisplay, this.formatValueForFilter(this.minKnob.value), this.formatValueForFilter(this.maxKnob.value));
            });
        });

        this.updateRangeDisplay();
    }

    onParameterLoadedFromSearch(event) {
        if (this.tiedDropdown.fromSearchId == event.parameter) {
            this.currentMinValue = event.value;
            this.lastKnob = this.minKnob;
            this.onRangeFilterUpdated(this, this.currentMinValue, this.currentMaxValue);
            this.loadRange();
        }

        if (this.tiedDropdown.toSearchId == event.parameter) {
            this.currentMaxValue = event.value;
            this.lastKnob = this.maxKnob;
            this.onRangeFilterUpdated(this, this.currentMinValue, this.currentMaxValue);
            this.loadRange();
        }
    }

    resetValue(event) {
        if (this.tiedDropdown.fromSearchId == event.searchid) {
            this.currentMinValue = this.startMinValue;
            this.minKnob.value = this.currentMinValue;
            this.loadRange();
        }

        if (this.tiedDropdown.toSearchId == event.searchid) {
            this.currentMaxValue = this.startMaxValue;
            this.maxKnob.value = this.currentMaxValue;
            this.loadRange();
        }
    }

    resetSlider() {
        this.currentMinValue = this.startMinValue;
        this.currentMaxValue = this.startMaxValue;

        document.dispatchEvent(new CustomEvent('range-reset', {
            bubbles: true,
            composed: true,
            detail: { sliderId: this.range.id },
        }));

        this.loadRange();
    }

    updateRangeDisplay() {
        setRangeDisplay(this.rangeDisplay, this.formatValueForFilter(this.currentMinValue), this.formatValueForFilter(this.currentMaxValue));
    }

    formatValueForFilter(value) {
        return value;
    }

    onRangeFilterUpdated(filter, min, max) {
        this.currentMinValue = min;
        this.currentMaxValue = max;
        const rangeUpdateEvent = new Event("rangeUpdated");
        rangeUpdateEvent.min = min;
        rangeUpdateEvent.max = max;
        rangeUpdateEvent.id = this.range.id;

        if (this.lastKnob == this.minKnob) {
            const fakeChangeMin = new Event("input");
            Object.defineProperty(fakeChangeMin, 'target', { writable: false, value: this.tiedDropdown.inputFrom });
            fakeChangeMin.target.value = min;
            this.tiedDropdown.inputFrom.dispatchEvent(fakeChangeMin);
        }

        if (this.lastKnob == this.maxKnob) {
            const fakeChangeMax = new Event("input");
            Object.defineProperty(fakeChangeMax, 'target', { writable: false, value: this.tiedDropdown.inputTo });
            fakeChangeMax.target.value = max;
            this.tiedDropdown.inputTo.dispatchEvent(fakeChangeMax);
        }

        document.dispatchEvent(rangeUpdateEvent);
    }
}