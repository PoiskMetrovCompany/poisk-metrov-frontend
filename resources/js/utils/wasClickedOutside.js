let lastClicked = undefined;

document.addEventListener("DOMContentLoaded", () => {
    document.addEventListener("click", (event) => lastClicked = event.target);
});

export function wasClickedOutside(element) {
    let parents = [];
    let currentParent = lastClicked;

    while (currentParent || currentParent != null) {
        parents.push(currentParent);
        currentParent = currentParent.parentElement;
    }

    return parents.includes(element.parentElement);
}