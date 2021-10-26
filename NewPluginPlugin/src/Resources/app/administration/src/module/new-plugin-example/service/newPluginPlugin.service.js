import ApiService from 'src/core/service/api.service';


export default class NewPluginPluginService{

    constructor(httpClient) {
        this.httpClient = httpClient;
    }

    joke() {
        return this.httpClient
            .get('/show-products-ajax')
            .then(response => response.data)
    }

}