export function getInputByLegendName(form, legendName, inputType = "input") {
    const formFieldsets = Array.from(form.getElementsByTagName("fieldset"));
    let input = undefined;

    formFieldsets.forEach(formFieldset => {
        const fieldsetLegend = Array.from(formFieldset.getElementsByTagName("legend"))[0];
        const fieldsetInput = Array.from(formFieldset.getElementsByTagName(inputType))[0];
        if (fieldsetLegend.textContent == legendName) {
            input = fieldsetInput;
        }
    });
    return input;
}