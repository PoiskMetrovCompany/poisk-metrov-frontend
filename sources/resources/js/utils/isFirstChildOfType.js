export function isFirstChildOfType(element) {
    const elementClass = element.className;
    const similar = element.parentElement.getElementsByClassName(elementClass);

    return element == similar[0];
}