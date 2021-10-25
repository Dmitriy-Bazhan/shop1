import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'michelis-e-button',
    label: 'michelis.cms.elements.michelis-e-button.label',
    component: 'michelis-e-button-component',
    configComponent: 'michelis-e-button-config',
    previewComponent: 'michelis-e-button-preview',
    defaultConfig: {
        url: {
            source: 'static',
            value: null
        },
        btnLabel: {
            source: 'static',
            value: 'Button name'
        },
        newTab: {
            source: 'static',
            value: false
        }
    }
});