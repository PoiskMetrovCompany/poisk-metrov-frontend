const ids = [];

export class Checkbox {
    checkboxElement;
    defaultClassName;
    status = false;

    constructor(checkboxElement) {
        this.checkboxElement = checkboxElement;
        this.defaultClassName = this.checkboxElement.className;
        this.checkboxElement.setAttribute("checkboxid", ids.length);
        ids.push(this);
    }

    checked(newStatus) {
        this.status = newStatus;
        this.checkboxElement.className = this.status ? this.defaultClassName + ' checked' : this.defaultClassName;
    }
}

export function getCheckbox(index) { return ids[index] };
