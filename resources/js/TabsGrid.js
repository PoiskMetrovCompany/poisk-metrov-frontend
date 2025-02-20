export class TabsGrid {
    enableClass = "enabled";
    disableClass = "disabled";
    buttonClass = "tab";
    currentButton = null;

    buttons = undefined;

    constructor(buttonsID, onClick, allowDeselect = false) {
        this.buttons = document.getElementById(buttonsID);
        if (this.buttons == undefined) {
            return;
        }
        Array.from(this.buttons.children).forEach(button => button.addEventListener("click", () => {
            if (!allowDeselect) {
                if (this.currentButton != button) {
                    onClick(button);
                    this.setActive(button);
                }
            }
            else {
                onClick(button);
            }
        }));
    }

    setButtonIcon(button, oldStyle, newStyle) {
        const buttonIcon = button.querySelector(".icon");
        if (buttonIcon)
            buttonIcon.className = buttonIcon.className.replace(oldStyle, newStyle);
    }

    setActive(button) {
        if (this.currentButton != null) {
            this.currentButton.className = this.buttonClass + " " + this.disableClass;
            this.setButtonIcon(this.currentButton, this.enableClass, this.disableClass);
        }
        this.currentButton = button;
        if (this.currentButton != null) {
            this.currentButton.className = this.buttonClass + " " + this.enableClass;
            this.setButtonIcon(this.currentButton, this.disableClass, this.enableClass);
        }
    }

    setFirstAsCurrent() {
        this.currentButton = Array.from(this.buttons.children)[0];
    }
}