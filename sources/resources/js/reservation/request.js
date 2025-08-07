import axios from "axios";

/**
 * @abstract
 */
class AbstractRequestBuilder {
    constructor() {
        if (new.target === AbstractRequestBuilder) {
            throw new TypeError("Cannot instantiate abstract class.");
        }
        this.url = import.meta.env.VITE_URL;
    }

    /**
     * @param {string} url
     */
    getUrl(url) {
        throw new Error("Method 'getUrl()' must be implemented.");
    }

    /**
     * @param {any} attributes
     */
    initBody(attributes) {
        throw new Error("Method 'initBody()' must be implemented.");
    }

    /**
     * @returns {Promise}
     */
    executePostQuery() {
        throw new Error("Method 'executePostQuery()' must be implemented.");
    }
}

/**
 * @see AbstractRequestBuilder
 */
class RequestBuilder extends AbstractRequestBuilder {
    constructor() {
        super();
        this.body = null;
    }

    /**
     * @override
     * @param {string} url
     */
    getUrl(url) {
        this.url = `${this.url}/${url}`;
    }

    /**
     * @override
     * @param {any} attributes
     */
    initBody(attributes) {
        this.body = attributes;
    }

    /**
     * @override
     * @returns {Promise}
     */
    async executePostQuery() {
        return await axios.post(this.url, this.body, {
            headers: {
                "Content-Type": "application/json",
            },
        });
    }
}

/**
 * @see RequestBuilder
 */
class StoreReservationRequest extends RequestBuilder
{
    constructor() {
        super();
    }

     call() {
        this.getUrl('reservations/form/store');
        this.initBody({
            fio: document.querySelector('#fio').value,
            birth_date: document.querySelector('#birth_date').value,
            citizenship: document.querySelector('#citizenship').innerHTML,
            education: document.querySelector('#education').innerHTML,
            marital_status: document.querySelector('#marital_status').innerHTML,
            presence_of_сhildren: document.querySelector('#presence_of_сhildren').innerHTML,
            monthly_income: document.querySelector('#monthly_income').value,
            work_company_name: document.querySelector('#work_company_name').value,
            work_inn: document.querySelector('#work_inn').value,
            work_phone_employer: document.querySelector('#work_phone_employer').value,
            work_job_title: document.querySelector('#work_job_title').value,
            work_sub_employment_contract: document.querySelector('#work_sub_employment_contract').innerHTML,
            work_sub_position_category: document.querySelector('#work_sub_position_category').innerHTML,
            work_sub_count_developers: document.querySelector('#work_sub_count_developers').innerHTML,
            work_sub_staging: document.querySelector('#work_sub_staging').innerHTML,
            document_type: document.querySelector('#document_type').innerHTML,
            type_of_employment_contract: document.querySelector('#type_of_employment_contract').innerHTML,
            passport_num: document.querySelector('#passport_num').value,
            passport_date_access: document.querySelector('#passport_date_access').value,
            passport_code: document.querySelector('#passport_code').value,
            passport_accessor: document.querySelector('#passport_accessor').value,
            passport_place_of_birth: document.querySelector('#passport_place_of_birth').value,
            passport_registration_address: document.querySelector('#passport_registration_address').value,
        });
        const request =  this.executePostQuery();
        request.then((response) => {
            console.log(response);
        });
        request.catch((error) => {
            console.log(error);
        });
    }
}

/// Вызов кала
document.querySelector('#reservation-store').addEventListener('click', function() {
    const storeReservationRequest = new StoreReservationRequest();
    storeReservationRequest.call();
});
