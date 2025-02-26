export function setRangeData(filter, display, min, max, onRangeFilterUpdated, formatter) {
    filter.setAttribute("min-range", min);
    filter.setAttribute("max-range", max);

    format(display, min, max, formatter)
    //TODO: call onRangeFilterUpdated(filter, min, max); and fix all related bugs

    window.addEventListener('range-changed', (e) => {
        const data = e.detail;
        if (data.sliderId == filter.id) {
            format(display, data.minRangeValue, data.maxRangeValue, formatter);
            onRangeFilterUpdated(filter, data.minRangeValue, data.maxRangeValue);
        }
    });

    function format(display, min, max, formatter) {
        if (typeof formatter == 'function')
            setRangeDisplay(display, formatter(min), formatter(max));
        else
            setRangeDisplay(display, min, max);
    }
}

export function setRangeDisplay(rangeDisplayElement, min, max) {
    const minDisplay = rangeDisplayElement.querySelectorAll(".number")[0];
    const maxDisplay = rangeDisplayElement.querySelectorAll(".number")[1];
    if (minDisplay !== undefined)
        minDisplay.textContent = min;
    if (maxDisplay !== undefined)
        maxDisplay.textContent = max;
}

function setRangeSelectorHeight(id, height) {
    var rangeSelector = document.getElementById(id);
    var min = rangeSelector.shadowRoot.getElementById("min");
    var max = rangeSelector.shadowRoot.getElementById("max");
    min.style.height = height;
    max.style.height = height;
}