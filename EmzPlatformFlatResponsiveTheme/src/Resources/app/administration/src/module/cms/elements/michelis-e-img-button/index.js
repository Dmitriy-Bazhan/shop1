import './component';
import './config';

Shopware.Service('cmsService').registerCmsElement({
    name: 'michelis-e-img-button',
    label: 'michelis.cms.elements.michelis-e-img-button.label',
    component: 'sw-cms-michelis-e-image-component',
    configComponent: 'sw-cms-config-michelis-e-image',
    defaultConfig: {
        media: {
            source: 'static',
            value: null,
            required: true,
            entity: {
                name: 'media',
            },
        },
    },
});
