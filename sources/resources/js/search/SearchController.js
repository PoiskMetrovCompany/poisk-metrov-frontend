export class SearchController {
    searchItems = [];

    constructor() {
        document.searchController = this;
    }

    loadParametersFromSearchBar() {
        const urlParams = new URLSearchParams(window.location.search);
        const parameters = [];

        for (let [parameter, values] of urlParams.entries()) {
            const split = values.split(',');

            if (parameter.includes('[')) {
                parameter = parameter.split('[')[0];
            }

            split.forEach(value => {
                let condition = '=';
                let field = parameter;
                value = decodeURIComponent(value);

                if (parameter.endsWith("-from")) {
                    condition = '>=';
                    field = field.replace("-from", "");
                }

                if (parameter.endsWith("-to")) {
                    condition = '<=';
                    field = field.replace("-to", "");
                }

                const newParameter = {};

                newParameter.field = field;
                newParameter.condition = condition;
                newParameter.value = value;
                newParameter.parameter = parameter;

                parameters.push(newParameter);
            });
        }

        parameters.forEach(parameter => {
            const event = new Event("parameterLoadedFromSearch");
            Object.assign(event, parameter);
            document.dispatchEvent(event);
        });

        const event = new Event("allParametersLoadedFromSearch");
        event.parameters = parameters;
        document.dispatchEvent(event);
    }

    addOrRemoveItemWithId(addObject) {
        const { searchid } = addObject;
        const index = this.searchItems.findIndex(searchItem => searchItem.searchid == searchid);

        if (index != -1) {
            this.removeItem(this.searchItems[index]);
        } else {
            this.addItem(addObject);
        }
    }

    removeItemWithId(searchid) {
        const index = this.searchItems.findIndex(searchItem => searchItem.searchid == searchid);

        if (index != -1) {
            this.removeItem(this.searchItems[index]);
        }
    }

    addItem(newItem) {
        if (this.getItemWithId(newItem.searchid)) {
            return;
        }

        this.searchItems.push(newItem);

        if (newItem.checkbox) {
            newItem.checkbox.checked(true);
        }

        const addedEvent = new Event("searchItemAdded");
        Object.assign(addedEvent, newItem);

        document.dispatchEvent(addedEvent);
    }

    addItemGroup(items) {
        let itemsWithEvents = [];

        items.forEach(newItem => {
            if (this.getItemWithId(newItem.searchid)) {
                return;
            }

            itemsWithEvents.push(newItem);
            this.searchItems.push(newItem);

            if (newItem.checkbox) {
                newItem.checkbox.checked(true);
            }
        });

        itemsWithEvents.forEach(item => {
            const addedEvent = new Event("searchItemAdded");
            Object.assign(addedEvent, item);
            document.dispatchEvent(addedEvent);
        });
    }

    removeItem(item) {
        const searchid = item.searchid;

        if (item.checkbox) {
            item.checkbox.checked(false);
        }

        const removed = this.searchItems.find(searchItem => searchItem.searchid == searchid);
        this.searchItems = this.searchItems.filter(searchItem => searchItem.searchid != searchid);

        const removedEvent = new Event("searchItemRemoved");
        Object.assign(removedEvent, removed);
        document.dispatchEvent(removedEvent);
    }

    getItemWithId(searchid) {
        return this.searchItems.find(item => item.searchid == searchid);
    }

    clearAll() {
        this.searchItems.forEach(item => this.removeItem(item));
        const clearAllEvent = new Event("searchItemsCleared");
        document.dispatchEvent(clearAllEvent);
    }

    sendSearchParams(request) {
        let searchParams = new URLSearchParams(request);
        let url = "/catalogue";
        searchParams.set('offset', 0);
        searchParams.set('limit', 18);

        for (const [field, value] of Object.entries(request)) {
            if (Array.isArray(value)) {
                searchParams.set(`${field}[]`, value[0]);

                for (let i = 1; i < value.length; i++) {
                    searchParams.append(`${field}[]`, value[i]);
                }
            }
        }

        if (searchParams.size > 0) {
            url += "?" + searchParams.toString();

            window.open(url, "_self");
        }
    }

    submitSearch() {
        const requestWithArrays = {};
        const request = {};

        this.searchItems.forEach(searchItem => {
            let fieldName = searchItem.field;
            let isArray = true;

            switch (searchItem.condition) {
                case '>=':
                    fieldName = `${searchItem.field}-from`;
                    isArray = false;
                    break;
                case '<=':
                    fieldName = `${searchItem.field}-to`;
                    isArray = false;
                    break;
                case '<>':
                    fieldName = `${searchItem.field}-not`;
                    isArray = false;
                    break;
            }

            if (searchItem.secondaryField) {
                isArray = false;
                request[searchItem.secondaryField] = searchItem.secondaryValue;
            }

            if (isArray) {
                if (!requestWithArrays[fieldName]) {
                    requestWithArrays[fieldName] = [];
                }

                requestWithArrays[fieldName].push(encodeURIComponent(searchItem.value));
                request[fieldName] = requestWithArrays[fieldName];
            } else {
                request[fieldName] = searchItem.value;
            }
        });

        this.sendSearchParams(request);
    }
}