import './component';
import './config';

Shopware.Service('cmsService').registerCmsElement({
    name: 'michelis-e-top-categories-down',
    label: 'michelis.cms.elements.michelis-e-top-categories-down.label',
    component: 'michelis-e-top-categories-down-component',
    configComponent: 'michelis-e-top-categories-down-config',
    defaultConfig: {
        media: {
            source: 'static',
            value: null,
            required: true,
            entity: {
                name: 'media'
            }
        },
        title: {
            source: 'static',
            value: null
        },
        description: {
            source: 'static',
            value: null
        },
        url: {
            source: 'static',
            value: null
        },
        btnLabel: {
            source: 'static',
            value: null
        },
        newTab: {
            source: 'static',
            value: false
        },
        titleSecond: {
            source: 'static',
            value: null
        },
        descriptionSecond: {
            source: 'static',
            value: null
        },
        urlSecond: {
            source: 'static',
            value: null
        },
        btnLabelSecond: {
            source: 'static',
            value: null
        },
        newTabSecond: {
            source: 'static',
            value: false
        },
        titleThird: {
            source: 'static',
            value: null
        },
        descriptionThird: {
            source: 'static',
            value: null
        },
        urlThird: {
            source: 'static',
            value: null
        },
        btnLabelThird: {
            source: 'static',
            value: null
        },
        newTabThird: {
            source: 'static',
            value: false
        }
    }
});
