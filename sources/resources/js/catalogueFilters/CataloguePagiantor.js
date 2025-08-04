import { Paginator } from "../paginators/paginator";

export class CataloguePaginator {
    searchStartEvent;
    searchEndEvent;

    basePath = window.location.href;
    currentPage = 1;

    paginator;
    cataloguefilters;
    baseTitle;
    baseDescription;

    constructor(searchStartEvent, searchEndEvent, cataloguefilters) {
        this.searchStartEvent = searchStartEvent;
        this.searchEndEvent = searchEndEvent;
        this.cataloguefilters = cataloguefilters;

        document.addEventListener(this.searchStartEvent, () => this.onSearchStart());
        document.addEventListener(this.searchEndEvent, (event) => this.onSearchEnd(event));

        this.loadPaginator();

        this.baseTitle = document.title;
        const pageMeta = Array.from(document.getElementsByTagName("meta"));

        pageMeta.forEach(meta => {
            if (meta.getAttribute("name") == "description") {
                this.baseDescription = meta.getAttribute("content");
            }
        });
    }

    onSearchStart() {
        const currPaginator = document.getElementById('paginator-with-show-more');

        if (currPaginator) {
            currPaginator.parentElement.style.opacity = 0.5;
            currPaginator.parentElement.style.pointerEvents = "none";
        }
    }

    deletePaginator() {
        const currPaginator = document.getElementById('paginator-with-show-more');

        if (currPaginator) {
            const parent = currPaginator.parentElement;
            parent.remove();
        }

        // history.pushState({}, '', this.basePath);
        this.paginator = null;
    }

    loadPaginator() {
        this.paginator = new Paginator();
        this.paginator.buttonsGridId = 'catalogue-paginator';
        this.paginator.pageClass = 'catalogue-page';
        this.paginator.pagesContainerId = 'building-cards-grid';
        this.paginator.currentPage = 0;
        this.currentPage = 1;

        const currUrl = window.location.href;

        if (currUrl.includes('page=')) {
            const pageStart = currUrl.indexOf('page=') + 5;
            this.paginator.currentPage = Number(currUrl.substring(pageStart)) - 1;
            this.currentPage = this.paginator.currentPage + 1;
        } else {
            this.paginator.currentPage = this.currentPage - 1;
        }

        this.paginator.load();

        this.paginator.buttons.forEach(button => {
            if (!button.className.includes('hidden')) {
                button.className = this.paginator.buttonClass;
            }
        });

        if (this.paginator.buttons[this.paginator.currentPage]) {
            this.paginator.buttons[this.paginator.currentPage].className = this.paginator.buttonClass + ' current';
        }

        this.paginator.textButtons[0].className = this.paginator.currentPage == 0 ? this.paginator.textButtonClass + ' disabled' : this.paginator.textButtonClass;
        this.paginator.textButtons[1].className = this.paginator.currentPage == this.paginator.buttons.length - 1 ? this.paginator.textButtonClass + ' disabled' : this.paginator.textButtonClass;

        const showAll = document.getElementById('show-all-paginator');
        showAll.className = this.currentPage == this.paginator.buttons.length ? this.paginator.textButtonClass + ' disabled' : this.paginator.textButtonClass;

        if (this.currentPage < this.paginator.buttons.length) {
            showAll.addEventListener('click', () => this.showAll());
        }

        this.paginator.setPage = (pageNum) => this.setPage(pageNum);
    }

    showAll() {
        this.cataloguefilters.offset = 0;
        this.cataloguefilters.limit = 1000;
        history.pushState({}, '', this.basePath);
        this.cataloguefilters.searchController.submitSearch();
    }

    setPage(pageNum) {
        const buttonCount = this.paginator.buttons.length;

        if (pageNum >= 0 && pageNum < buttonCount) {
            this.paginator.buttons[this.paginator.currentPage].className = this.paginator.buttonClass;

            if (this.paginator.currentPage > this.paginator.offset && this.paginator.currentPage < buttonCount - this.paginator.offset - 1 && this.paginator.hideButtons) {
                this.paginator.buttons[this.paginator.currentPage].className += ' hidden';
            }

            this.paginator.currentPage = pageNum;

            if (!this.paginator.hideButtons) {
                this.paginator.buttons[this.paginator.currentPage].className = this.buttonClass + ' current';

                this.paginator.buttons.forEach((button, i) => {
                    const isCurrentPage = i == this.paginator.currentPage;
                    this.paginator.pages[i].style.display = isCurrentPage ? 'grid' : 'none';
                });
                return;
            }

            this.paginator.buttons.forEach((button, i) => {
                const isCurrentPage = i == this.paginator.currentPage;

                if (isCurrentPage) {
                    button.className = this.paginator.buttonClass + ' current';
                } else if (i > this.paginator.offset && i < buttonCount - this.paginator.offset - 1) {
                    button.className = this.paginator.buttonClass + ' hidden';
                }
            });

            if (this.paginator.ellipseStart) {
                this.paginator.ellipseStart.className = this.paginator.buttonClass + (this.paginator.currentPage > (this.paginator.offset + 3) ? '' : ' hidden');
            }

            if (this.paginator.ellipseEnd) {
                this.paginator.ellipseEnd.className = this.paginator.buttonClass + (this.paginator.currentPage < buttonCount - (this.paginator.offset + 4) ? '' : ' hidden');
            }

            for (let i = Math.max((this.paginator.offset + 1), this.paginator.currentPage - 2); i < this.paginator.currentPage + 3 && i < buttonCount - (this.paginator.offset + 1); i++) {
                if (i != this.paginator.currentPage) {
                    this.paginator.buttons[i].className = this.paginator.buttonClass;
                }
            }

            history.pushState({}, '', this.basePath + '/page=' + (pageNum + 1));
            this.updateMetaPage(pageNum);
            this.searchWithPagination(pageNum);
        }
    }

    updateMetaPage(pageNum) {
        const pageMeta = Array.from(document.getElementsByTagName("meta"));

        pageMeta.forEach(meta => {
            if (meta.getAttribute("name") == "description") {
                meta.setAttribute("content", this.baseDescription + " Стр. " + (pageNum + 1));
            }
        });

        document.title = this.baseTitle + ", стр. " + (pageNum + 1);
    }

    searchWithPagination(pageNum) {
        this.cataloguefilters.offset = pageNum * this.cataloguefilters.limit;
        window.scrollTo({ top: 0, left: 0, behavior: "instant" });
        this.cataloguefilters.searchController.submitSearch();
    }

    onSearchEnd(event) {
        this.deletePaginator();

        const catalogueContainer = document.getElementById('catalogue-container');
        const paginatorContainer = document.createElement('div');
        paginatorContainer.innerHTML += event.response.data.paginator;
        catalogueContainer.appendChild(paginatorContainer);

        const showAll = document.getElementById('show-all-paginator');
        showAll.style.display = event.response.data.catalogueItems.length > 0 ? "flex" : "none";

        this.loadPaginator();
    }
}