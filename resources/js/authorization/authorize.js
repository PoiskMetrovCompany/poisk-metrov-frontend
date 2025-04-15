import axios from "axios";

function phoneInputForm() {
    const phoneForm = document.getElementById("login-form-phone");
    const failure = document.getElementById("login-failure");
    const timerField = document.getElementById("code-timer");
    const phonePopup = document.getElementById("login-form-phone-popup");
    const codePopup = document.getElementById("login-form-code-popup");
    const codePopupPhoneDisplay = document.getElementById("sent-number-id");
    const sendCodeAgain = document.getElementById("send-code-again-button")
    const codeForm = document.getElementById("login-form-code");
    const codeInput = document.getElementById("authorize-code");
    const personalInfo = document.getElementById("personal-info");
    const personalInfoForm = document.getElementById("personal-info-form");
    // const success = document.getElementById("login-success");

    const authPopups = Array.from(document.getElementsByClassName("auth-popup background"));

    authPopups.forEach(popup => {
        let popupCloseButton = popup.querySelector(".icon.action-close");

        if (popup) {
            popupCloseButton.addEventListener("click", () => popup.style.display = "none");
        }
    });

    let timer = 59;
    let timerInterval = undefined;
    let formData;
    let phoneNumber;
    let waitingForCodeConfirmation = false;

    if (!codeInput) {
        return;
    }

    codeInput.addEventListener("change", () => confirmCode());
    codeInput.addEventListener("keyup", () => confirmCode());
    codeInput.addEventListener("submit", (event) => {
        confirmCode();
        return false;
    });

    personalInfoForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        let userInfo = new FormData(event.target);
        userInfo.set('phone', phoneNumber);
        event.target.style.opacity = 0.5;
        const response = await axios.post("/api/v1/user/update", userInfo);
        console.log(response)
        if (!response.status.toString().startsWith("2") || response.data.status == "error") {
            personalInfo.style.display = "none";
            failure.style.display = "block";
            console.error(response.data.data);
        } else {
            const authResponse = await axios.post("/api/authorize-user", formData);
            window.location.reload();
        }
    });

    async function confirmCode() {
        if (!phoneNumber ) {//|| codeInput.value.toString().length != 4 || waitingForCodeConfirmation) {
            return;
        }

        waitingForCodeConfirmation = true;
        formData.set("pincode", codeInput.value);
        const response = await axios.post("/api/v1/users/account/authorization", formData);
        waitingForCodeConfirmation = false;

        if (response.status.toString().startsWith("2")) {
            if (response.data.status === "Authorization success") {
                window.location.reload();
            } else if (response.data.status === "NeedFill") {
                codePopup.style.display = "none";
                personalInfo.style.display = "block";
            }
        } else {
            codePopup.style.display = "none";
            failure.style.display = "block";
            console.error(response.data);
        }
    }

    phoneForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        if (timerInterval !== undefined) {
            return;
        }

        formData = new FormData(event.target);
        phoneNumber = formData.get("phone");
        codePopupPhoneDisplay.textContent = phoneNumber;
        event.target.style.opacity = 0.5;
        sendCodeAgain.className = "common-button disabled";
        const response = await axios.post("/api/v1/users/account/authentication", formData);
        event.target.style.opacity = 1;
        phonePopup.style.display = "none";
        console.log(response)
        if (!response.status.toString().startsWith("2") || response.status === "error") {
            failure.style.display = "block";
        } else {
            codePopup.style.display = "block";
            codeInput.focus();
            timerInterval = setInterval(updateTime, 1000);
        }
    });

    sendCodeAgain.addEventListener("click", async () => {
        if (!formData || timerInterval !== undefined) {
            return;
        }

        codeForm.style.opacity = 0.5;
        sendCodeAgain.className = "common-button disabled";
        // const response = await axios.post("/api/confirm-user", formData);
        const response = await axios.post("/api/v1/users/account/authentication", formData);
        codeForm.style.opacity = 1;

        if (!response.status.toString().startsWith("2")) {
            failure.style.display = "block";
        } else {
            codePopup.style.display = "block";
            timerInterval = setInterval(updateTime, 1000);
        }
    });

    function updateTime() {
        const unitVal = timer;

        if (unitVal > 9) {
            timerField.textContent = unitVal;
        } else {
            timerField.textContent = "0" + unitVal;
        }

        timer -= 1;

        if (timer === -1) {
            clearInterval(timerInterval);
            timer = 59;
            timerInterval = undefined;
            sendCodeAgain.className = "common-button";
        }
    }
}

document.addEventListener("DOMContentLoaded", () => phoneInputForm());
