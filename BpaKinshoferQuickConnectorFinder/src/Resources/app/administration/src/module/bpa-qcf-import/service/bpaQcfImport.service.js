import ApiService from 'src/core/service/api.service';

/**
 * @private
 */
export default class BpaQcfImportService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'bpa-qcf-import') {
        super(httpClient, loginService, apiEndpoint);
        this.name = 'bpaQcfImportService';
        this.httpClient = httpClient;
    }

    async import(file, callback, config = {}) {

        const formData = new FormData();
        if (file) {
            formData.append('bpaCsvFile', file);
        }

        Object.entries(config).forEach(([key, value]) => {
            formData.append(`config[${key}]`, value);
        });
        console.log(formData)
        const newProgress = await this.httpClient.post('/bpa-qcf-import/process', formData, {
            headers: this.getBasicHeaders()
        });

        callback.call(this, newProgress.data);

        return newProgress;
    }
}
