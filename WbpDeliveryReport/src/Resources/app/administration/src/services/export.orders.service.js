import ApiService from 'src/core/service/api.service';

export default class exportOrdersService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'export-orders') {
        super(httpClient, loginService, apiEndpoint);
        this.name = 'exportOrdersService';
        this.httpClient = httpClient;
    }

    async export(data) {
        const criteriaReport = data
        const headers = this.getBasicHeaders();

        return await this.httpClient.post('/_action/wbp-report/order/export', criteriaReport, {
            headers
        })
    }
}
