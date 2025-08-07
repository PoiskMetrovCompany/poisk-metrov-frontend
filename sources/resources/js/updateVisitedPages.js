import axios from "axios";
import { getCookie, setCookie } from "./cookies";

export function updateVisitedPages(cookieName) {
    if (pageType == undefined) {
        return;
    }

    let visitedPages = getCookie(cookieName);

    if (visitedPages == null) {
        visitedPages = [];
    } else {
        visitedPages = visitedPages.split(',');
    }

    const urlParts = window.location.pathname.split('/');
    const page = pageType;
    const code = urlParts[urlParts.length - 1];

    if (!visitedPages.includes(code)) {
        visitedPages.push(code);
    }

    visitedPages = visitedPages.join(',');

    setCookie(cookieName, visitedPages, 365);

    //Set in script in document-layout
    if (isUserAuthorized) {
        axios.post('/api/update-pages-visited', { page: page, code: code });
    }
}