import { getInputsFromForm, clearFormInputs, sendForm, lastFormRequestResolved } from '../forms';
import { validateName } from '../inputPatternValidation/name';
import { validatePhone } from '../inputPatternValidation/tel';
import {validateLastName} from "../inputPatternValidation/lastName.js";
import {validateMiddleName} from "../inputPatternValidation/middleName.js";

export class CRMForm {
    currentButton = null;
    preferredContact = null;
    url = null;

    requiredFields = [];

    form = undefined;

    getComment = undefined;
    onSuccess = undefined;
    onFailure = undefined;

    constructor(formID, formButtonsID, url, requiredFields, getComment, onSuccess, onFailure) {
        this.form = document.getElementById(formID);

        if (this.form == undefined) {
            // console.error("Form " + formID + " not found");
            return;
        }

        this.url = url;
        this.requiredFields = requiredFields;
        this.getComment = getComment;
        this.onSuccess = onSuccess;
        this.onFailure = onFailure;

        this.form.addEventListener("submit", (e) => this.onSubmit(e));

        if (formButtonsID == undefined)
            return;

        const buttonContainer = document.getElementById(formButtonsID);
        if (buttonContainer != undefined) {
            const buttons = Array.from(buttonContainer.children);
            const startButton = localStorage.getItem("chosen-contact");

            buttons.forEach(button => {
                button.addEventListener("click", () => this.setActive(button));
                if (this.currentButton == undefined && startButton == null || startButton == button.dataset.name) {
                    this.setActive(button);
                }
            });
            localStorage.removeItem("chosen-contact");
        }
    }

    async onSubmit(e) {
        e.preventDefault();
        console.log(this.form)
        console.log(this.requiredFields)
        let inputs = getInputsFromForm(this.form, this.requiredFields);
        let nameValidate = validateName(this.form);
        let lastNameValidate = validateLastName(this.form);
        let middleNameValidate = validateMiddleName(this.form);
        let phoneValidate = validatePhone(this.form);

        if (!(nameValidate && lastNameValidate && middleNameValidate && phoneValidate)) {
            return;
        }
        let socialNetworkName = undefined;
        if (this.preferredContact != null && this.currentButton != null) {
            socialNetworkName = this.currentButton.dataset.name;
        }

        let comment = this.getComment(socialNetworkName);
        let bodyJSON = inputs;

        if (bodyJSON["name"]) {
            bodyJSON["name"] = "САЙТ! " + `${bodyJSON["name"]} ${bodyJSON["last_name"]} ${bodyJSON["middle_name"]}`;
            console.log(bodyJSON);
        } else {
            bodyJSON["name"] = "САЙТ!";
        }

        bodyJSON["comment"] = comment;

        if (this.preferredContact != null) {
            bodyJSON["contact"] = this.preferredContact;
        }

        bodyJSON["city"] = currentCityCached;

        let onSuccess = this.onSuccess != undefined ? this.onSuccess : function () {
            console.log("Form sent");
        }

        let onFailure = this.onFailure != undefined ? this.onFailure : function () {
            console.log("Failed to send form");
        }
        await sendForm(this.url, bodyJSON, onSuccess, onFailure);
        if (lastFormRequestResolved)
            clearFormInputs(this.form);
    }

    setButtonIcon(button, oldStyle, newStyle) {
        const buttonIcon = button.querySelector(".icon");
        buttonIcon.className = buttonIcon.className.replace(oldStyle, newStyle);
    }

    setActive(button) {
        if (this.currentButton != null) {
            this.currentButton.className = "tab disabled";
            this.setButtonIcon(this.currentButton, "enabled", "disabled");
        }
        this.currentButton = button;
        this.currentButton.className = "tab enabled";
        this.setButtonIcon(this.currentButton, "disabled", "enabled");

        const buttonType = button.querySelector(".icon").className.split(" ")[1];
        switch (buttonType) {
            case "whatsapp":
            case "telegram":
                this.preferredContact = buttonType;
                break;
            //Do nothing
            default:
                this.preferredContact = null;
                break;
        }
    }
}
