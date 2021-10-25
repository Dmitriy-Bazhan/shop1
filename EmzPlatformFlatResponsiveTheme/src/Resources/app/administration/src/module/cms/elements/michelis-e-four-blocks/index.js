import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'michelis-e-four-blocks',
    label: 'michelis.cms.elements.michelis-e-four-blocks.label',
    component: 'michelis-e-four-blocks-component',
    configComponent: 'michelis-e-four-blocks-config',
    previewComponent: 'michelis-e-four-blocks-preview',
    defaultConfig: {
        media: {
            source: 'static',
            value: null,
            required: true,
            entity: {
                name: 'media'
            }
        },
        url: {
            source: 'static',
            value: null
        },
        newTab: {
            source: 'static',
            value: false
        }
    }
});