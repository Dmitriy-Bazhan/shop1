import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';
import LocalStorage from 'src/helper/storage/storage.helper';
import DomAccess from 'src/helper/dom-access.helper';
import ElementLoadingIndicatorUtil from 'src/utility/loading-indicator/element-loading-indicator.util';

export default class NewPluginFirstJs extends Plugin {

    static options = {
        buttonSubmitSelector: '.new-plugin-js-test',
        crmLogo: '.header-logo-picture',
        button: '#ajax-button',
        textdiv: '#ajax-display'
    };

    init() {

        this._client = new HttpClient();
        this.button = DomAccess.querySelector(this.el, this.options.button);
        this.textdiv = DomAccess.querySelector(this.el, this.options.textdiv);
        this._registerEvents();

        try {
            this._submitButton = DomAccess.querySelector(this.el, this.options.buttonSubmitSelector);

        } catch (e) {

        }
        if (this._submitButton) {
            this._submitButton.addEventListener('click', function () {
                alert('I pushed button');
            });
        }
    }

    _registerEvents() {
        this.button.onclick = this._fetch.bind(this);
    }

    _fetch() {
        this._client.get('/show-products-ajax', this._setContent.bind(this), 'application/json', true)
    }

    _setContent(data) {
        let products = JSON.parse(data);

        let str = '';
        for (let i in products.products) {
            str = str + '<p>' + products.products[i].name + '</p>';
        }

        this.textdiv.innerHTML = str;
    }


}