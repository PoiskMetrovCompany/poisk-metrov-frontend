import axios from "axios";
import { Paginator } from "../paginators/paginator";

export class NewsPaginator {
    basePath = window.location.href;
    currentPage = 1;
    paginator;

    constructor() {
        this.paginator = new Paginator();
        this.paginator.pageClass = 'news-page';
        this.paginator.buttonsGrid = 'news-paginator';
        this.paginator.pagesContainerId = 'news-conteiner';
        this.loadNewsPage();
    }

    loadPaginator() {
        this.paginator = new Paginator();
        this.paginator.pageClass = 'news-page';
        this.paginator.buttonsGridId = 'news-paginator';
        this.paginator.pagesContainerId = 'news-conteiner';
        this.paginator.currentPage = 0;
        this.currentPage = 1;

        this.paginator.load();

        this.paginator.buttons.forEach(button => {
            if (!button.className.includes('hidden')) {
                button.className = this.paginator.buttonClass;
            }
        });
        
        this.paginator.buttons[this.paginator.currentPage].className = this.paginator.buttonClass + ' current';
        const showAll = document.getElementById('show-all-paginator');
        showAll.style.display = 'none';

        this.paginator.setPage = (pageNum) => this.setPage(pageNum);
    }

    async setPage(pageNum) {
        const buttonCount = this.paginator.buttons.length;

        if (pageNum >= 0 && pageNum < buttonCount) {
            this.paginator.buttons[this.paginator.currentPage].className = this.paginator.buttonClass;

            if (this.paginator.currentPage > this.paginator.offset && this.paginator.currentPage < buttonCount - this.paginator.offset - 1 && this.paginator.hideButtons) {
                this.paginator.buttons[this.paginator.currentPage].className += 'hidden';
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

            this.currentPage = pageNum + 1;
            await this.loadNewsPage();

            this.paginator.buttons.forEach(button => {
                if (!button.className.includes('hidden')) {
                    button.className = this.paginator.buttonClass;
                }
            });
            
            this.paginator.buttons[pageNum].className = this.paginator.buttonClass + ' current';
        }
    }

    async loadNewsPage() {
        
        const offset = (this.currentPage - 1) * 9;
        const response = await axios.get('news-cards', {params: {offset: offset}});
        const currPaginator = document.getElementById('news-paginator');

        if(currPaginator) {
            const parent = currPaginator.parentElement;
            parent.remove();
        }

        const newsContainer = document.getElementById('news-conteiner');
        const pageContainer = document.getElementById('news-page');
        const paginatorContainer = document.createElement('div');
        paginatorContainer.innerHTML += response.data.paginator;
        newsContainer.appendChild(paginatorContainer);
        pageContainer.innerHTML = '';

        response.data.views.forEach(card => {
            pageContainer.innerHTML += card;
        });

        this.loadPaginator();
        window.scrollTo({ top: 0, left: 0, behavior: "instant" });
    }
}