function smoothScroll(target, duration) {
    const targetElement = document.querySelector(target);
    if (!targetElement) return;

    const targetPosition = targetElement.offsetTop;
    const startPosition = window.pageYOffset;
    const distance = targetPosition - startPosition;
    let startTime = null;

    function animation(currentTime) {
        if (startTime === null) startTime = currentTime;
        const timeElapsed = currentTime - startTime;
        const run = easeInOutCubic(timeElapsed, startPosition, distance, duration);
        window.scrollTo(0, run);
        if (timeElapsed < duration) requestAnimationFrame(animation);
    }

    function easeInOutCubic(t, b, c, d) {
        t /= d / 2;

        if (t < 1) {
            return (c / 2) * t * t * t + b;
        }

        t -= 2;
        return (c / 2) * (t * t * t + 2) + b;
    }

    requestAnimationFrame(animation);
}
