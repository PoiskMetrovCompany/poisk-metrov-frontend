import { round10 } from "../decimalAdjust";
import { SearchRange } from "./SearchRange";

export class PriceRange extends SearchRange {
    formatValueForFilter(value) {
        return round10(value / 1000000, 0);
    }

    onRangeFilterUpdated(filter, min, max) {
        this.currentMinValue = min;
        this.currentMaxValue = max;
        const rangeUpdateEvent = new Event("rangeUpdated");
        rangeUpdateEvent.min = min / 1000000;
        rangeUpdateEvent.max = max / 1000000;
        rangeUpdateEvent.id = this.range.id;

        if (this.lastKnob == this.minKnob) {
            const fakeChangeMin = new Event("input");
            Object.defineProperty(fakeChangeMin, 'target', { writable: false, value: this.tiedDropdown.inputFrom });
            fakeChangeMin.target.value = min / 1000000;
            this.tiedDropdown.inputFrom.dispatchEvent(fakeChangeMin);
        }

        if (this.lastKnob == this.maxKnob) {
            const fakeChangeMax = new Event("input");
            Object.defineProperty(fakeChangeMax, 'target', { writable: false, value: this.tiedDropdown.inputTo });
            fakeChangeMax.target.value = max / 1000000;
            this.tiedDropdown.inputTo.dispatchEvent(fakeChangeMax);
        }

        document.dispatchEvent(rangeUpdateEvent);
    }
}