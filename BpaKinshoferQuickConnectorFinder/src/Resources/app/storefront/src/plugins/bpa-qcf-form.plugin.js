import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';
import LocalStorage from 'src/helper/storage/storage.helper';
import DomAccess from 'src/helper/dom-access.helper';
import ElementLoadingIndicatorUtil from 'src/utility/loading-indicator/element-loading-indicator.util';

export default class BpaQcfForm extends Plugin {

    static options = {
        selectManufacturerSelector: '#bpaQcfManufacturer',
        selectTypeSelector: '#bpaQcfType',
        buttonSubmitSelector: '#bpaQcfBtnSubmit'
    };

    init() {
        this._httpClient = new HttpClient();
        this._selectManufacturer = DomAccess.querySelector(this.el, this.options.selectManufacturerSelector);
        this._selectType = DomAccess.querySelector(this.el, this.options.selectTypeSelector);
        this._submitButton = DomAccess.querySelector(this.el, this.options.buttonSubmitSelector);
        this._registerEventsManufacturersList();
        this._registerEventsTypesList();

        if (LocalStorage.getItem('productManufactureId')) {
            this._httpClient.post(window.router['bpa.qcf.connector.finder.types'],
                JSON.stringify({
                    'manufacturerId': LocalStorage.getItem('productManufactureId'),
                    '_csrf_token': window.bpaQcfConnectorFinderTypesToken
                }), (response) => {
                    if (this._selectType) {
                        this._selectType.innerHTML = response;
                    }
                })
        }
    }

    _registerEventsManufacturersList() {
        if(this._selectManufacturer) {
            const that = this;

            const manufacture = document.getElementById('qcf-product-manufacture')
            manufacture.val = LocalStorage.getItem('productManufactureId')

            this._selectManufacturer.addEventListener('change', function() {
                ElementLoadingIndicatorUtil.create(that.el);
                LocalStorage.setItem('productManufactureId',this.value)

                if(that._submitButton) {
                    that._submitButton.setAttribute('disabled', 'disabled');
                }
                that._httpClient.post(window.router['bpa.qcf.connector.finder.types'],
                    JSON.stringify({
                        'manufacturerId': LocalStorage.getItem('productManufactureId'),
                        '_csrf_token': window.bpaQcfConnectorFinderTypesToken
                    }), (response) => {
                        if(that._selectType) {
                            that._selectType.innerHTML = response;
                        }
                        ElementLoadingIndicatorUtil.remove(that.el);
                    })
            });
        }
    }

    _registerEventsTypesList() {
        if(this._selectType) {
            const that = this;

            this._selectType.removeEventListener('change', function() {});
            this._selectType.addEventListener('change', function() {
                // if(this.value && that._submitButton) {
                //     that._submitButton.removeAttribute('disabled');
                // }
                if (LocalStorage.getItem('productManufactureId') && this.value) {
                    that._submitButton.removeAttribute('disabled');
                }
            });
        }
    }

}
