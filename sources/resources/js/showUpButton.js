document.addEventListener("DOMContentLoaded", () => {
    const footer = document.querySelector("footer");
    const upButton = document.querySelector(".up-button");

    if (!footer || !upButton) {
        return;
    }

    const defaultClassName = upButton.className;

    document.addEventListener("scroll", () => {
        const rect = footer.getBoundingClientRect();
        const isFooterVisible = rect.top <= (window.innerHeight || document.documentElement.clientHeight) || window.scrollY >= 1200;
        upButton.className = isFooterVisible ? defaultClassName + " visible" : defaultClassName;
    });
})