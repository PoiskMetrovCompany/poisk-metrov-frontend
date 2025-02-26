export function openMenu(menuID) {
    const menu = document.getElementById(menuID);
    if (menu == undefined)
        return;
    menu.style.display = "grid";
    const closeButton = menu.querySelector(".sub-menus.close");
    menu.addEventListener("click", (e) => {
        if (e.target == menu) {
            menu.style.display = "none";
        }
    });
    if (closeButton) {
        closeButton.addEventListener("click", () => menu.style.display = "none");
    }
}

function addMenuToButton(buttonID, menuID) {
    const button = document.getElementById(buttonID);
    if (button) {
        button.addEventListener("click", () => {
            if (menuID == "make-meeting") {
                const form = document.getElementById(menuID);
                const officeField = form.querySelector('#office-address');
                officeField.value = selectOffice(buttonID);
            }

            openMenu(menuID);
        });
    }
}

function initializeMenu() {
    addMenuToButton("leave-request-menu", "consult-request");
    addMenuToButton("leave-request-marquee", "consult-request");
    addMenuToButton("favorites-leave-request", "consult-request");
    addMenuToButton("favorites-leave-request-mobile", "consult-request");
    addMenuToButton("leave-request-menu-mortgage", "consult-request");
    addMenuToButton("leave-request-favorites", "consult-request");
    addMenuToButton("reserve-request", "consult-request");
    addMenuToButton("learn-more-mortgage", "learn-mortgage");
    addMenuToButton("reserve-button", "reserve-apartment");
    addMenuToButton("reserve-button-mobile", "reserve-apartment");
    addMenuToButton("download-presentation-button", "get-real-estate-presentation-menu");
    addMenuToButton("whatsapp-presentation-button", "get-real-estate-presentation-menu");
    addMenuToButton("telegram-presentation-button", "get-real-estate-presentation-menu");
    addMenuToButton("open-infrastructure-menu", "infrastructure-menu");
    addMenuToButton("learn-about-first-sale-menu-button", "learn-about-first-sale-menu");
    addMenuToButton("favorites-login", "login-form-phone-popup");
    addMenuToButton("favorites-login-mobile", "login-form-phone-popup");
    addMenuToButton("favorites-login-plan-button", "login-form-phone-popup");
    addMenuToButton("favorites-login-building-button", "login-form-phone-popup");
    addMenuToButton("login-button", "login-form-phone-popup");
    addMenuToButton("login-button-mobile", "login-form-phone-popup");
    addMenuToButton("order-call", "signup-base");
    addMenuToButton("order-call-mobile", "signup-base");
    addMenuToButton("get-catalogue-whatsapp", "get-free-catalogue-popup");
    addMenuToButton("get-catalogue-telegram", "get-free-catalogue-popup");
    addMenuToButton("make-meeting-koshurnicova", "make-meeting");
    addMenuToButton("make-meeting-kovalchuk", "make-meeting");
    addMenuToButton("make-meeting-parfenovskaya", "make-meeting");
    addMenuToButton("revert-ads-agreement-link", "revert-ads-agreement");
}

function selectOffice(buttonId) {
    const offices = {
        "make-meeting-koshurnicova": 'Новосибирск, ул. Кошурникова 33',
        "make-meeting-kovalchuk": 'Новосибирск, ул. Дуси Ковальчук 276',
        "make-meeting-parfenovskaya": 'Санкт-Петербург, ул. Парфёновская, 12'
    };
    return offices[buttonId];
}

document.addEventListener("DOMContentLoaded", () => initializeMenu());