export class ClientTable extends HTMLElement {
    clientRows = [];

    constructor() {
        super();
    }

    load() {
        this.clientRows = Array.from(this.querySelectorAll('tr[type="client-row"]'));

        this.clientRows.forEach(row => {
            const rowOpenButton = row.querySelector("button");
            const clientId = row.getAttribute("clientid");

            rowOpenButton.addEventListener("click", () => {
                const requestRows = this.querySelectorAll(`tr[type="request-row"][clientid="${clientId}"]`);
                const shown = rowOpenButton.getAttribute("shown") == "true";

                rowOpenButton.setAttribute("shown", !shown);

                requestRows.forEach(requestRow => {
                    requestRow.setAttribute("shown", !shown);
                });
            });
        });
    }

    connectedCallback() {
        this.load();
    }
}