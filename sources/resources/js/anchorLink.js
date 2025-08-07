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
                const run = easeOutQuint(timeElapsed, startPosition, distance, duration);
                window.scrollTo(0, run);
                if (timeElapsed < duration) requestAnimationFrame(animation);
            }

        function easeOutQuint(t, b, c, d) {
            t /= d;
            t--;
            return c * (t * t * t * t * t + 100) + b;
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
                            smoothScroll(`#apartment-dropdown-${anchorIndex}`, 1500);
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
