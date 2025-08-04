import { getHiddenItemsWithCollection } from "../getHiddenItems";

export class Gallery {
    scrolledElementID;
    galleryElement;
    galleryItemsAsHTMLCollection;
    buttonBack;
    buttonForward;
    onElementChange;
    noDisable = false;
    isScrollOnClick = true;
    isButtonIcon = false;
    activeClass = "orange";

    constructor(galleryID, buttonsID, onCurrentElementChange, defaultColor = "orange", noDisable = false) {
        this.galleryElement = document.getElementById(galleryID);
        const buttons = document.getElementById(buttonsID);

        if (buttons == undefined)
            return;

        this.noDisable = noDisable;
        this.activeClass = defaultColor;
        this.buttonBack = buttons.children.item(0);
        this.buttonForward = buttons.children.item(1);
        this.galleryItemsAsHTMLCollection = [...this.galleryElement.children];

        this.galleryItemsAsHTMLCollection = this.galleryItemsAsHTMLCollection.filter(item => {
            const style = getComputedStyle(item);

            return !(style.display == "none" || style.position == "absolute" || style.position == "fixed");
        });

        if (this.galleryItemsAsHTMLCollection.length == 0) {
            this.buttonBack.style.display = "none";
            this.buttonForward.style.display = "none";
        }

        this.scrolledElementID = this.getGalleryHiddenItems().length;
        this.onElementChange = onCurrentElementChange;
        if (this.onElementChange != undefined)
            this.onElementChange(this.scrolledElementID, true);

        this.updateButtons();

        this.galleryElement.addEventListener("scroll", () => {
            this.updateButtons();
        });

        this.buttonBack.addEventListener("click", () => {
            if (this.isButtonEnabled(this.buttonBack))
                this.scrollBackward();
        })
        this.buttonForward.addEventListener("click", () => {
            if (this.isButtonEnabled(this.buttonForward))
                this.scrollForward();
        })

        this.galleryItemsAsHTMLCollection.forEach((element, i) => {
            element.addEventListener("click", () => {
                if (this.isScrollOnClick) {
                    this.scrollGalleryTo(i);
                }
            })
        });
    }

    isButtonEnabled(button) {
        return button.getAttribute("enabled") == "true";
    }

    getGalleryHiddenItems() {
        return getHiddenItemsWithCollection(this.galleryElement, this.galleryItemsAsHTMLCollection);
    }

    updateButtons() {
        this.scrolledElementID = this.getGalleryHiddenItems().length;
        const galleryItem = this.galleryItemsAsHTMLCollection[this.scrolledElementID];

        if (galleryItem) {
            this.setButtonActivity(this.buttonBack, this.galleryElement.scrollLeft <= galleryItem.clientWidth);
            this.setButtonActivity(this.buttonForward, this.galleryElement.scrollLeft + this.galleryElement.clientWidth + galleryItem.clientWidth / 2 >= this.galleryElement.scrollWidth);
        }
    }

    setButtonActivity(button, disabled) {
        const oldStyle = disabled ? this.activeClass : "disabled";
        const newStyle = disabled ? "disabled" : this.activeClass;
        const icon = this.isButtonIcon ? button : button.querySelector(".icon");
        icon.className = icon.className.replace(oldStyle, newStyle);
        button.setAttribute("enabled", !disabled);

        if (!this.isButtonEnabled(this.buttonBack) && !this.isButtonEnabled(this.buttonForward) && !this.noDisable) {
            this.buttonBack.style.display = "none";
            this.buttonForward.style.display = "none";
        } else {
            this.buttonBack.style.display = "flex";
            this.buttonForward.style.display = "flex";
        }
    }

    scrollGalleryTo(elementNumber) {
        if (elementNumber < 0 || this.scrolledElementID == elementNumber) {
            return;
        }

        this.scrolledElementID = elementNumber;
        //Очень важно иметь конкрентое значение расстояния в пикселях, иначе скролл не будет работать 
        const gap = Number.parseInt(getComputedStyle(this.galleryElement).gap);
        let x = 0;
        let y = 0;

        this.galleryItemsAsHTMLCollection.forEach((element, i) => {
            if (i < this.scrolledElementID && element.offsetWidth > 0) {
                x += element.offsetWidth + gap;
            }
        });

        if (this.onElementChange != undefined) {
            this.onElementChange(this.scrolledElementID);
        }

        this.galleryElement.scrollTo({ left: x, top: y, behavior: "smooth" });
    }

    scrollGallery(offset = 0) {
        this.scrollGalleryTo(Math.min(this.scrolledElementID + offset, this.galleryItemsAsHTMLCollection.length - 1));
    }

    scrollForward() {
        this.scrollGallery(1);
    }

    scrollBackward() {
        this.scrollGallery(-1);
    }
}