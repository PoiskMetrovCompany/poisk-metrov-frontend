export function sharePageMenu(buttonId, getLink = getCurrentPageLink) {
    const socialMediaButton = document.getElementById(buttonId);
    const socialMediaMenu = socialMediaButton.getElementsByClassName("with-border")[0];
    const buttons = socialMediaButton.getElementsByClassName("text-with-icon");
    const copyLinkButton = buttons[0];
    const shareTelegram = buttons[1];
    const shareWatsapp = buttons[2];
    const closeLinkButton = buttons[3];
    const link = getLink();

    socialMediaMenu.addEventListener("click", (event) => event.stopPropagation());

    socialMediaButton.onclick = () => switchMenu();
    socialMediaButton.addEventListener("focusout", () => switchMenu(true));

    function switchMenu(off = false) {
        const style = window.getComputedStyle(socialMediaMenu);
        if (style.width == "0px" && !off) {
            socialMediaMenu.className = socialMediaMenu.className.replace("menu", "menu open");
            socialMediaButton.className = socialMediaButton.className.replace("card-button", "card-button orange");
        } else {
            socialMediaMenu.className = socialMediaMenu.className.replace("menu open", "menu");
            socialMediaButton.className = socialMediaButton.className.replace("card-button orange", "card-button");
        }
    }

    copyLinkButton.addEventListener("click", () => {
        navigator.clipboard.writeText(link);
        switchMenu();
    });

    closeLinkButton.addEventListener("click", () => {
        switchMenu();
    });

    shareTelegram.addEventListener("click", () => {
        window.open("https://t.me/share/url?url=" + link);
    });

    shareWatsapp.addEventListener("click", () => {
        window.open("https://api.whatsapp.com/send?text=" + link);
    });
}

function getCurrentPageLink() {
    return window.location.href;
}