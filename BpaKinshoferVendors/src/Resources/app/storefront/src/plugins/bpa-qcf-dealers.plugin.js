import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';
import LocalStorage from 'src/helper/storage/storage.helper';

export default class BpaQcfDealersPlugin extends Plugin {

    init() {
        let storeLabel = 'label dsa'
        let storeStreet = 'street'
        let storeStreetNumber = '312'
        let storeCountry = 'country'

        document.getElementsByClassName('dealer-info-btn').value = storeLabel
        document.getElementById('store-label').textContent = storeLabel
        document.getElementById('store-street').textContent = storeStreet
        document.getElementById('store-street-number').textContent = storeStreetNumber
        document.getElementById('store-country').textContent = storeCountry
    }
}
