document.addEventListener("DOMContentLoaded", () => {
    const ids = Array.from(document.querySelectorAll("#custom-dropdown-id"));
    
    ids.forEach(id => {
        const dropdownContainer = document.getElementById(id.value + "-custom-dropdown");
        const selectHeader=  Array.from(dropdownContainer.getElementsByClassName("static-custom-dropdown select-header"))[0];
        const dropdown = Array.from(dropdownContainer.getElementsByClassName("static-custom-dropdown base-container"))[0];
        const items = Array.from(dropdownContainer.getElementsByClassName("static-custom-dropdown item"));
        
        dropdownContainer.setAttribute("tabindex", -1);

        selectHeader.addEventListener("click", () => {
            dropdown.style.overflow = "visible";  
        });

        items.forEach(item => {
            const content = item.textContent;
            const textContainer = selectHeader.children[0];
            item.addEventListener("click", () => {
                textContainer.textContent = content;
                textContainer.className = "static-custom-dropdown selected-content";
                dropdown.style.overflow = "hidden";
            });
        });

        dropdownContainer.addEventListener('focusout', (event) => {
            dropdown.style.boxShadow = 'none';
            dropdown.style.overflow = "hidden";
        });
    });
});

export function getCustomSelect(placeholder, fieldId) {
    const container = document.getElementById(fieldId + "-custom-dropdown");
    const selectedItem = Array.from(container.getElementsByClassName("static-custom-dropdown select-header"))[0];
    let selectedValue = undefined;

    if (selectedItem.children[0].textContent !== placeholder) {
        selectedValue = selectedItem.children[0].textContent.trim();
    }

    return selectedValue;
}