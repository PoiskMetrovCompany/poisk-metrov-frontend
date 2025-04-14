document.addEventListener('DOMContentLoaded', () => {
    function scrollToAnchor() {
        const anchorElement = document.querySelector('.anchor');
        if (anchorElement) {
            anchorElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
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
                    if (dropdown) {
                        const dropdownHeaderElement = dropdown.previousElementSibling;
                        setTimeout(
                            () => { dropdownHeaderElement.click() },
                            500
                        );
                    }
                } catch (error) { }
            }

            scrollToAnchor();
        }
    }

    action();
});
