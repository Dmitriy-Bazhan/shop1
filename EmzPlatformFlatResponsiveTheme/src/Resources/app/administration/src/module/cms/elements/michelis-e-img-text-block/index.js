import './component';
import './config';

Shopware.Service('cmsService').registerCmsElement({
    name: 'michelis-e-img-text-block',
    label: 'michelis.cms.elements.michelis-e-img-text-block.label',
    component: 'michelis-e-img-text-block-component',
    configComponent: 'michelis-e-img-text-block-config',
    defaultConfig: {
        title: {
            source: 'static',
            value: ''
        },
        description: {
            source: 'static',
            value: ''
        },
        costFirst: {
            source: 'static',
            value: ''
        },
        costSecond: {
            source: 'static',
            value: ''
        }
    }
});