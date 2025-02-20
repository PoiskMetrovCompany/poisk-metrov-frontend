export let isProfileMenuOpen = false;

let openMenus = [];

function openProfileMenu(menu) {
    if (menu == undefined)
        return;

    menu.style.display = "grid";
    isProfileMenuOpen = true;
    openMenus.push(menu);

    menu.addEventListener("click", (e) => {
        if (e.target == menu) {
            closeProfileMenu(menu);
        }
    });

    const closeButton = menu.querySelector(".sub-menus.close");

    if (closeButton) {
        closeButton.onclick = () => closeProfileMenu(menu);
    }

    if (window.location.pathname.startsWith("/agent")) {
        const userMenu = document.querySelector("user-menu");

        if (userMenu) {
            userMenu.setAttribute("open", true);
        }
    }
}

function closeProfileMenu(menu) {
    menu.style.display = "none";
    openMenus = openMenus.filter(openMenu => openMenu != menu);
    isProfileMenuOpen = openMenus.length > 0;
}

document.addEventListener("DOMContentLoaded", () => {
    if (!isUserAuthorized) {
        return;
    }

    const profileButtonIds = ["profile-button", "profile-button-mobile"];
    const profileButtons = [];
    profileButtonIds.forEach(id => profileButtons.push(document.getElementById(id)));

    profileButtons.forEach(profileButton => {
        if (!profileButton) {
            return;
        }

        let profileMenu = profileButton.parentElement.querySelector(".profile-menu.container");

        profileButton.onclick = (event) => {
            openProfileMenu(profileMenu);
        }
    });
});