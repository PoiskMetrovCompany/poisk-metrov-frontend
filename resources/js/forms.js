export function getInputsFromForm(form, inputIds) {
    let formInputs = [];
    const requiredComponents = ["input", "select"];
    requiredComponents.forEach(component => formInputs.push(...Array.from(form.getElementsByTagName(component))));

    let values = {};

    formInputs.forEach(input => {
        if (inputIds.includes(input.id) && values[input.id] === undefined)
            values[input.id] = input.value;
    })
    return values;
}

export function clearFormInputs(form) {
    const formInputs = Array.from(form.getElementsByTagName("input"));
    formInputs.forEach(input => {
        if (input.type != "submit")
            input.value = ""
    });
}

export let lastFormRequestResolved = true;

export async function sendForm(URL, bodyJSON, onFormSendSuccess, onFormSendFailure) {
    if (lastFormRequestResolved)
        lastFormRequestResolved = false;
    else
        return;

    const body = JSON.stringify(bodyJSON);
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const requestData = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrf
        },
        body: body
    }
    const loader = document.getElementById("loader-overlay");
    if (loader)
        loader.style.display = "block";
    const requestResult = await fetch(URL, requestData);
    const json = await requestResult.json();
    if (loader)
        loader.style.display = "none";
    if (json.message == "success")
        onFormSendSuccess();
    else
        onFormSendFailure();

    lastFormRequestResolved = true;
}