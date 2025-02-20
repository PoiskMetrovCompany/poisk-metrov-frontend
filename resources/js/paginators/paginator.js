export class Paginator {
    pagesContainerId = null;
    pageClass = null;
    pages = null;
    buttonsGridId = null;
    currentPage = 0;
    textButtons = null;
    buttons = null;
    buttonsGrid = null;
    pagesContainer = null;
    pages = null;

    textButtonClass = "paginator text-button";
    buttonClass = "paginator page-button";
    ellipseStart = null;
    ellipseEnd = null;
    offset = 2;
    hideButtons = true;

    //Set variables and call load
    load() {
        this.buttonsGrid = document.getElementById(this.buttonsGridId);
        this.pagesContainer = document.getElementById(this.pagesContainerId);
        this.pages = Array.from(this.pagesContainer.getElementsByClassName(this.pageClass));
        this.textButtons = Array.from(this.buttonsGrid.getElementsByClassName(this.textButtonClass));
        this.buttons = Array.from(this.buttonsGrid.getElementsByClassName(this.buttonClass));
        this.ellipses = this.buttons.filter(button => button.textContent == '...');
        this.buttons = this.buttons.filter(button => button.textContent != '...');
        this.textButtons[0].onclick = () => this.setPage(this.currentPage - 1);
        this.textButtons[1].onclick = () => this.setPage(this.currentPage + 1);
        const numberedButtonsGrid = this.buttonsGrid.getElementsByClassName("paginator buttons-grid")[0];

        if (numberedButtonsGrid.childElementCount > 9) {
            const startPosition = this.offset;
            const endPosition = numberedButtonsGrid.childElementCount - this.offset - 1;

            this.ellipseStart = document.createElement("div")
            this.ellipseStart.className = this.buttonClass;
            this.ellipseStart.textContent = "...";
            this.ellipseStart.onclick = () => {
                this.setPage(this.currentPage - 3);
            }

            numberedButtonsGrid.children[startPosition].insertAdjacentElement('afterend', this.ellipseStart);

            this.ellipseEnd = document.createElement("div")
            this.ellipseEnd.className = this.buttonClass;
            this.ellipseEnd.textContent = "...";
            this.ellipseEnd.onclick = () => {
                this.setPage(this.currentPage + 3);
            }

            numberedButtonsGrid.children[endPosition].insertAdjacentElement('afterend', this.ellipseEnd);

            for (let i = startPosition + 1; i < endPosition; i++) {
                this.buttons[i].className += " hidden";
            }

            this.ellipseEnd.className += " hidden";
        }

        this.buttons.forEach((button, i) => button.onclick = () => this.setPage(i));
    }

    setPage(pageNum) {
        const buttonCount = this.buttons.length;

        if (pageNum >= 0 && pageNum < buttonCount) {
            this.buttons[this.currentPage].className = this.buttonClass;

            if (this.currentPage > this.offset && this.currentPage < buttonCount - this.offset - 1 && this.hideButtons) {
                this.buttons[this.currentPage].className += " hidden";
            }

            this.currentPage = pageNum;
            this.textButtons[0].className = this.currentPage == 0 ? this.textButtonClass + " disabled" : this.textButtonClass;
            this.textButtons[1].className = this.currentPage == buttonCount - 1 ? this.textButtonClass + " disabled" : this.textButtonClass;

            if (!this.hideButtons) {
                this.buttons[this.currentPage].className = this.buttonClass + " current";

                this.buttons.forEach((button, i) => {
                    const isCurrentPage = i == this.currentPage;
                    this.pages[i].style.display = isCurrentPage ? "grid" : "none";
                });
                return;
            }

            this.buttons.forEach((button, i) => {
                const isCurrentPage = i == this.currentPage;
                this.pages[i].style.display = isCurrentPage ? "grid" : "none";

                if (isCurrentPage) {
                    button.className = this.buttonClass + " current";
                } else if (i > this.offset && i < buttonCount - this.offset - 1) {
                    button.className = this.buttonClass + " hidden";
                }
            });

            if (this.ellipseStart) {
                this.ellipseStart.className = this.buttonClass + (this.currentPage > (this.offset + 3) ? "" : " hidden");
            }

            if (this.ellipseEnd) {
                this.ellipseEnd.className = this.buttonClass + (this.currentPage < buttonCount - (this.offset + 4) ? "" : " hidden");
            }

            for (let i = Math.max((this.offset + 1), this.currentPage - 2); i < this.currentPage + 3 && i < buttonCount - (this.offset + 1); i++) {
                if (i != this.currentPage) {
                    this.buttons[i].className = this.buttonClass;
                }
            }
        }
    }
}