export function initializeInfrastructureMenu(menu) {
    if (!menu) {
        return;
    }
    
    const infraButtons = Array.from(menu.getElementsByClassName("item"));
    const selectAllButton = menu.getElementsByClassName("location menu select-all")[0];

    const infMetroButton = infraButtons[0];
    const infSchoolButton = infraButtons[1];
    const infChildrenButton = infraButtons[2];
    const infParksButton = infraButtons[3];
    const infShopsButton = infraButtons[4];
    const infSportButton = infraButtons[5];
    const infHealthButton = infraButtons[6];

    const map = document.getElementById("map");

    const allButtons = [infMetroButton, infSchoolButton, infChildrenButton, infParksButton, infShopsButton, infSportButton, infHealthButton];

    allButtons.forEach(button => button.addEventListener("click", () => toggleButton(button)))
    selectAllButton.addEventListener("click", () => {
        const allSelected = !areAllButtonsSelected();
        allButtons.forEach(button => setSelection(button, allSelected))
    });

    let selections = [];

    function areAllButtonsSelected() {
        return selections.length == allButtons.length;
    }

    function toggleButton(button) {
        const selectionStatus = selections.includes(button.id);
        setSelection(button, !selectionStatus);
    }

    function setSelection(button, isSelected) {
        if (button === undefined) {
            return;
        }
        const defaultItemClass = "infrastructure menu item";
        const buttonIcon = button.getElementsByClassName("icon-container")[0].getElementsByClassName("icon")[0];
        const buttonCheck = button.querySelector(".check");

        const contentType = buttonIcon.className.split(" ")[1];
        Array.from(map.getElementsByClassName(contentType)).forEach(element => {
            element.parentNode.style.display = isSelected ? "flex" : "none";
        })

        button.className = defaultItemClass;
        if (isSelected) {
            if (!selections.includes(button.id))
                selections.push(button.id);
            button.className += " orange";
            buttonIcon.className = buttonIcon.className.replace("black", "orange");
            buttonCheck.style.visibility = "visible";
        }
        else {
            selections = selections.filter(selection => selection != button.id);
            buttonIcon.className = buttonIcon.className.replace("orange", "black");
            buttonCheck.style.visibility = "hidden";
        }
        selectAllButton.className = "location menu select-all";
        if (areAllButtonsSelected()) {
            selectAllButton.className += " selected";
        }
    }
}