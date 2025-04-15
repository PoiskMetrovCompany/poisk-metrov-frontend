document.addEventListener('DOMContentLoaded', () => {
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

    function action() {
        if (window.location.hash) {
            const linkParse = decodeURIComponent(window.location.hash.substring(1));
            const dropdownHeaders = document.querySelectorAll('#type_apartment');
            const anchors = Array.from(dropdownHeaders).map(el => el.innerHTML.trim());

            const anchorElement = document.querySelector('.anchor');
            if (anchorElement) {
                anchorElement.setAttribute('id', linkParse);
            }

            const anchorIndex = anchors.findIndex(anchor => anchor === linkParse);
            if (anchorIndex !== -1) {
                try {
                    const dropdown = document.querySelector(`#apartment-dropdown-${anchorIndex}`);

                    let isScrolled = false;

                    dropdown.addEventListener('animationend', (event) => {
                        if (event.animationName === 'customFadeInDown' && !isScrolled) {
                            smoothScroll(`#apartment-dropdown-${anchorIndex}`, 1000);
                            isScrolled = true;
                        }
                    }, { once: true });

                    if (dropdown) {
                        const dropdownHeaderElement = dropdown.previousElementSibling;
                        setTimeout(() => { dropdownHeaderElement.click(); }, 1100);
                    }
                } catch (error) { }
            }
        }
    }

    action();
});
