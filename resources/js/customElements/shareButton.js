import { wasClickedOutside } from "../utils/wasClickedOutside";

export class ShareButtonCustomElement extends HTMLElement {
    constructor() {
        super();
    }

    load() {
        this.addEventListener("click", (event) => {
            if (event.target == this) {
                this.setAttribute("open", this.getAttribute("open") != 'true');
            }
        });

        this.addEventListener("blur", (event) => {
            if (wasClickedOutside(this)) {
                setTimeout(() => this.setAttribute("open", false), 100);
            }
        });

        this.copyLinkButton = this.querySelectorAll("button")[0];
        this.shareTelegram = this.querySelectorAll("button")[1];
        this.shareWhatsapp = this.querySelectorAll("button")[2];

        this.copyLinkButton.addEventListener("click", () => {
            navigator.clipboard.writeText(this.getLink());
            this.setAttribute("open", false);
        });

        this.shareTelegram.addEventListener("click", () => {
            window.open("https://t.me/share/url?url=" + this.getLink());
            this.setAttribute("open", false);
        });

        this.shareWhatsapp.addEventListener("click", () => {
            window.open("https://api.whatsapp.com/send?text=" + this.getLink());
            this.setAttribute("open", false);
        });
    }

    getLink = () => window.location.href;

    connectedCallback() {
        setTimeout(() => this.load());
    }
}